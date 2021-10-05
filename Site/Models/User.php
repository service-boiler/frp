<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Http\Response;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ServiceBoiler\Online\OnlineChecker;
use ServiceBoiler\Rbac\Concerns\RbacUsers;
use ServiceBoiler\Prf\Site\Concerns\Schedulable;
use ServiceBoiler\Prf\Site\Concerns\AttachParents;
use ServiceBoiler\Prf\Site\Concerns\AttachFlRoleRequest;
use ServiceBoiler\Prf\Site\Concerns\AttachFerroliManagerRegions;
use ServiceBoiler\Prf\Site\Contracts\Addressable;
use ServiceBoiler\Prf\Site\Contracts\Messagable;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Services\Digift;
use ServiceBoiler\Prf\Site\Exceptions\Digift\DigiftException;
use \Illuminate\Database\Eloquent\Relations\MorphMany;

class User extends Authenticatable implements Addressable, Messagable
{

    use AttachParents, AttachFerroliManagerRegions, HasApiTokens, Notifiable, RbacUsers, OnlineChecker, Schedulable;
   // use AttachParents, AttachFerroliManagerRegions, Notifiable, RbacUsers, OnlineChecker, Schedulable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'first_name', 'last_name', 'middle_name', 'name_for_site','short_name', 'email', 'phone', 'mirror_id', 'password', 'dealer',
        'display', 'active', 'image_id', 'only_ferroli', 'verified','company_position',
        'warehouse_id', 'currency_id', 'region_id', 'type_id','repair_price_ratio','created_by','phone_verify_code','email_notify','phone_notify'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = [
        'logged_at',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function (User $user) {
            $user->verify_token = str_random(40);
            $user->price_type_id = config('site.defaults.user.price_type_id');
            $user->warehouse_id = config('site.defaults.user.warehouse_id');
            if(auth()->user()) {$user->created_by = auth()->user()->getAuthIdentifier();}
        });
    }

    /**
     * @return bool
     */
    public function hasGuid()
    {
        return !is_null($this->getAttribute('guid'));
    }

    /**
     * @return string
     */
    public function getLogoAttribute()
    {
        if (is_null($this->image_id)) {
            return Storage::disk('logo')->url('default.png');
        }

        return Storage::disk($this->image->storage)->url($this->image->path);
    }

    public function copletedStages ($program_id)
    {
        return AcademyUserStage::join('academy_stages','academy_user_stages.stage_id','=','academy_stages.id')
            ->where('user_id',$this->getKey())->where('is_required','1')->where('program_id',$program_id)->where('completed','1')
            ->select('academy_user_stages.stage_id','academy_user_stages.created_at')->get();
    }


    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = $value ? preg_replace(config('site.phone.set.pattern'), config('site.phone.set.replacement'), $value) : null;
    }

    /**
     * Логотип
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function image()
    {
        return $this->belongsTo(Image::class)->withDefault([
            'storage' => 'images',
            'path' => 'logo/default.png',
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function unsubscribe()
    {
        return $this->hasOne(Unsubscribe::class, 'email', 'email');
    }


    public function addresses_count()
    {
        return $this->addresses()->count() + Address::where(function ($query) {
                $query->whereAddressableType('contragents')->whereIn('addressable_id', $this->contragents->pluck('id'));
            })->count();
    }


    public function userScopes()
    {
        $user_scopes=[];
        if($this->hasPermission('repairs')) {   $user_scopes[]='repairs';}
        if($this->hasPermission('orders')) {$user_scopes[]='orders';}
        if($this->hasPermission('mountings')) {$user_scopes[]='mountings';}
        if($this->hasPermission('retail-sale-reports')) {$user_scopes[]='retail_sale_reports';}
        if($this->hasPermission('ferroli_contacts')) {$user_scopes[]='ferroli_contacts';}
        if($this->hasPermission('manuals_for_sc')) {$user_scopes[]='manuals_for_sc';}
        return $user_scopes;
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }


    public function addressesActual()
    {
        return $this->addresses()->where('type_id',7)->orderByDesc('main');
    }
    public function addressActualMain()
    {
        return $this->addressesActual()->where('main',1)->whereHas('esbProduct', function($q){$q->where('enabled','1');})->first();
    }

    public function addressesPublic()
    {
        return $this->addresses()->where('type_id',2)->where('active',1)->where('show_ferroli',1)->where('approved',1);
    }
    public function eshopPublic()
    {
        return $this->addresses()->where('type_id',5)->where('active',1)->where('show_ferroli',1)->where('approved',1);
    }



    public function missionsCreated()
    {
        return $this->hasMany(Mission::class, 'created_by_user_id');
    }


    public function missions()
    {
        return $this->belongsToMany(Mission::class, 'mission_users','user_id','mission_id');
    }


    public function missionsMonth($month, $missions, $year = '2021')
    {

        $first_day = Carbon::createFromDate($year, $month, 1)->format('Y-m-d');
        $last_day = Carbon::createFromDate($year, $month,1)->endOfMonth()->format('Y-m-d');
        $mss=$missions;

        $ms=Mission::whereIn('id',$mss->pluck('id'));
        $ums = $ms->where('date_from', '<=',$last_day)->where('date_from', '>=',$first_day)->whereHas('missionUsers', function ($q) {$q->where('user_id', $this->id);});
        //->whereHas('missionUsers', function ($q) {$q->where('user_id', $this->id);})



        return $ums;
    }

    public function getStarsAttribute(){
        $countStars=1;

        if($this->hasRole('asc_esb')){
            $countStars=3;
        }elseif($this->hasRole('csc')){
            $countStars=2;
        }

        return $countStars;
    }

    public function revisionPartsCreated()
    {
        return $this->hasMany(RevisionPart::class, 'created_by_user_id');
    }

    public function ticketsCreated()
    {
        return $this->hasMany(Ticket::class, 'created_by_id');
    }
    public function ticketsReceiver()
    {
        return $this->hasMany(Ticket::class, 'receiver_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(UserType::class, 'type_id');
    }

    /**
     * @return Address
     */
    public function address()
    {
        return $this->addresses()->where('type_id', 2)->firstOrNew([]);
    }

    /**
     * @return Contact
     */
    public function sc()
    {
        return $this->contacts()->where('type_id', 2)->first();
    }

    /**
     * Контакты
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * Авторизации
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function authorizations()
    {
        return $this->hasMany(Authorization::class);
    }

    /**
     * Подтвержденные авторизации
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function authorization_accepts()
    {
        return $this->hasMany(AuthorizationAccept::class);
    }

    /**
     * Акты выполненных работ
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function acts()
    {
        return $this->hasMany(Act::class);
    }

    public function sc_phones()
    {
        $phones = collect([]);
        foreach ($this->contacts()->where('type_id', 2)->get() as $contact) {
            $phones = $phones->merge($contact->phones);
        }

        return $phones;
    }

    /**
     * Основной регион клиента
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }


    public function regionBizDistricts()
    {
        return $this->belongsToMany(
            RegionBizDistrict::class,
            'user_biz_region_relations',
            'user_id',
            'biz_district_id'
        );
    }

    public function regionBizDistrict()
    {
        if($this->regionBizDistricts()->exists()) {
            return $this->regionBizDistricts()->orderByDesc('created_at')->first();
        } else {
            return $this->region ? $this->region->biz_district : null;
        }

    }
    public function getRegionBizIdAttribute()
    {
        if($this->regionBizDistricts()->exists()) {
            return $this->regionBizDistricts()->orderByDesc('created_at')->first()->id;
        } else {
            return null;
        }

    }

    public function setRegionBizIdAttribute($value)
    {
        UserBizRegionRelation::updateOrCreate(['user_id'=>$this->id],['biz_district_id'=>$value]);
    }



    public function notifiMainRegions()
    {
        return $this->hasMany(Region::class, 'notification_address', 'email');

    }
    public function notifiRegions()
    {
        return $this->ferroliManagerRegions();

    }
    public function ferroliManagerMainRegions()
    {
        return Region::query()->where('notification_address',$this->email);
    }

    public function ferroliManagerRegions()
    {
        return $this->belongsToMany(Region::class,
            'region_manager_relations',
            'user_id',
            'region_id');
    }

    public function ferroliManagerStorehouses()
    {
        $notifiRegions = auth()->user()->notifiRegions;
        return Storehouse::whereHas('user',function ($query) {
            $query->whereIn('region_id',$this->notifiRegions->pluck('id'));
        });
    }

    public function ferroliManagerStorehousesZip()
    {
        $notifiRegions = auth()->user()->notifiRegions;
        return Storehouse::whereHas('user',function ($query) {
            $query->whereIn('region_id',$this->notifiRegions->pluck('id'))
                ->whereHas('roles', function ($query){$query->whereIn('name', ['csc']);})
                ->where('active','1');
        });
    }



    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }




    public function warehouses()
    {

        $result = collect([]);
        $result = $this->addresses()
            ->has('product_group_types')
            ->where('type_id', 6)
            ->get();

        $result = $result->merge(User::query()
                ->find(config('site.receiver_id'))
                ->addresses()
                ->has('product_group_types')
                ->where('type_id', 6)
                ->get());

        /*
                if ($this->hasRole(config('site.ferroli_shouse_roles', []), false) || $this->userRelationParents->where('enabled','1')->filter(function ($relation) {
                                return $relation->parent->hasRole(config('site.ferroli_shouse_roles', []), false);
                            })
                ) {
                    $result = $result->merge(User::query()
                        ->find(config('site.receiver_id'))
                        ->addresses()
                        ->has('product_group_types')
                        ->where('type_id', 6)
                        ->get());
                }
         */
        return $result;
    }
    /**
     * @return Collection
     */
    public function warehousesAll()
    {

        $result = collect([]);
        if($this->hasRole(config('site.warehouse_all_roles'), [])) {
            $result = Address::has('product_group_types')
                ->where('type_id', 6)->whereHas('user', function ($q){$q->where('id','<>',1);})
                ->get();
        }
        return $result;
    }
    /**
     * @return Collection
     */
    public function warehousesCart()
    {

        $result = collect([]);


        if ($this->getAttribute('only_ferroli') == 1) {

            $result = $result->merge(User::query()
                ->find(config('site.receiver_id'))
                ->addresses()
                ->has('product_group_types')
                ->where('type_id', 6)
                ->get());
        }

        if ($this->region && $this->getAttribute('only_ferroli') == 0) {
            $result = $result->merge(
                $this->region->warehouses()
                    ->where('addresses.addressable_type', 'users')
                    ->where('addresses.addressable_id', '!=', auth()->id())
                    ->has('product_group_types')
                    ->get()
                    ->filter(function ($address) {
                        return $address->addressable->hasRole(config('site.warehouse_check', []), false);
                    })
            );
        }


        return $result;
    }

    /**
     * @return Collection
     */
    public function distrWarehouses()
    {

        $result = collect([]);

        $creator = Auth()->user();


        if ($this->getAttribute('only_ferroli') == 1 || $creator->hasPermission('admin_stand_orders')) {

            $result = $result->merge(User::query()
                ->find(config('site.receiver_id'))
                ->addresses()
                ->has('product_group_types')
                ->where('type_id', 6)
                ->get());
        }

        if ($this->region && $this->getAttribute('only_ferroli') == 0) {
            $result = $result->merge(
                $this->region->warehouses()
                    ->where('addresses.addressable_type', 'users')
                    ->where('addresses.addressable_id', '!=', auth()->id())
                    ->has('product_group_types')
                    ->get()
                    ->filter(function ($address) {
                        return $address->addressable->hasRole(config('site.distr_warehouse_check', []), false);
                    })
            );


        }

        return $result;
    }

    public function distrWarehousesWithSelf()
    {

        $result = collect([]);

        $creator = Auth()->user();


        if ($this->getAttribute('only_ferroli') == 1 || $creator->hasPermission('admin_stand_orders')) {

            $result = $result->merge(User::query()
                ->find(config('site.receiver_id'))
                ->addresses()
                ->has('product_group_types')
                ->where('type_id', 6)
                ->get());
        }

        if ($this->region && $this->getAttribute('only_ferroli') == 0) {
            $result = $result->merge(
                $this->region->warehouses()
                    ->where('addresses.addressable_type', 'users')
                    //->where('addresses.addressable_id', '!=', auth()->id())
                    ->has('product_group_types')
                    ->get()
                    ->filter(function ($address) {
                        return $address->addressable->hasRole(config('site.distr_warehouse_check', []), false);
                    })
            );


        }

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function mounters()
    {
        return Mounter::query()
            ->whereHas('userAddress', function ($address) {
                $address
                    ->where('addressable_type', '=', 'users')
                    ->where('addressable_id', '=', $this->getAuthIdentifier());
            });//->orderBy('created_at', 'DESC')
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function contracts()
    {
        return Contract::query()->whereHas('contragent', function ($contragent) {
            $contragent->where('user_id', '=', $this->getAuthIdentifier());
        });
    }
    public function esbContracts()
    {
        return $this->hasMany(EsbContract::class,'service_id');
    }

    public function esbClientContracts()
    {
        return $this->hasMany(EsbContract::class,'client_user_id');
    }

    public function esbClientVizits()
    {
        return $this->hasMany(EsbUserVisit::class,'client_user_id');
    }

    /**
     * Склад
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Контрагенты
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contragents()
    {
        return $this->hasMany(Contragent::class);
    }

    /**
     * Склады дистрибьюторов
     */
    public function storehouses()
    {
        return $this->hasMany(Storehouse::class);
    }

    public function storehousesHaveProduct($product_id)
    {
        return $this->storehouses()->whereHas('products', function ($q) use ($product_id) {
            $q->where('product_id',$product_id);
        });
    }

    public function storehousesHaveProducts($products)
    {
        return ( $this->storehouses()->whereHas('products', function ($q) use ($products) {
            $q->whereIn('product_id',$products);
        })->get());
    }

    public function distributorSales()
    {
        return $this->hasMany(DistributorSale::class);
    }
    public function distributorSaleUrl()
    {
        return $this->hasOne(DistributorSaleUrl::class);
    }

    public function distributorSalesMonth($month, $distributorSales, $year = '2020')
    {
        $ds = collect();
        $sales = $distributorSales->where('month', $month)->where('user_id', $this->id)->groupBy('product_id');

        foreach($sales as $sale => $val) {
            $ds[]=['prodict_id'=>$sale, 'quantity'=>$val->sum('quantity'), 'sku'=>Product::find($sale)->sku, 'product_name'=>Product::find($sale)->name];
        }

        return $ds;
    }

    public function distributorSalesWeek ($week, $distributorSales)
    {
        $ds = collect();
        $sales = $distributorSales->where('week_id', $week)->where('user_id', $this->id)->groupBy('product_id');

        foreach($sales as $sale => $val) {
            $ds[]=['prodict_id'=>$sale, 'quantity'=>$val->sum('quantity'), 'sku'=>Product::find($sale)->sku, 'product_name'=>Product::find($sale)->name];
        }

        return $ds;
    }

    /**
     * Типы цен пользователя
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function prices()
    {
        return $this->hasMany(UserPrice::class);
    }

    /**
     * Заказы
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function orders_last_3()
    {
        return $this->hasMany(Order::class)->orderBy('created_at', 'DESC')->Limit(3);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function distributors()
    {
        return Order::query()->whereHas('address', function ($query) {
            $query
                ->where('type_id', 6)
                ->where('addressable_id', $this->getAttribute('id'))
                ->where('addressable_type', DB::raw('"users"'));
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function engineers()
    {
        return $this->hasMany(Engineer::class);
    }


    public function rateService()
    {
        return $this->hasOne(UserServiceRate::class,'user_id');
    }


    /**
     * Торговые организации
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trades()
    {
        return $this->hasMany(Trade::class);
    }

    public function childs()
    {
        return $this->belongsToMany(
            User::class,
            'user_relations',
            'parent_id',
            'child_id'
        );
    }
    public function childEngineers()
    {
        return $this->belongsToMany(
            User::class,
            'user_relations',
            'parent_id',
            'child_id'
        )->whereHas('roles', function ($query){
            $query->whereIn('name', ['service_fl']);
        });
    }
    public function childMounters()
    {
        return $this->belongsToMany(
            User::class,
            'user_relations',
            'parent_id',
            'child_id'
        )->whereHas('roles', function ($query){
            $query->whereIn('name', ['montage_fl']);
        });
    }

    public function parents()
    {
        return $this->belongsToMany(
            User::class,
            'user_relations',
            'child_id',
            'parent_id'
        )->orderByDesc('user_relations.created_at');
    }

    public function acceptedParents()
    {
        return $this->belongsToMany(
            User::class,
            'user_relations',
            'child_id',
            'parent_id'
        )->where('user_relations.accepted','1');
    }
    public function enabledParents()
    {
        return $this->belongsToMany(
            User::class,
            'user_relations',
            'child_id',
            'parent_id'
        )->where('user_relations.accepted','1')
            ->where('user_relations.enabled','1')
            ->orderByDesc('user_relations.created_at');
    }

    public function getParentAttribute()
    {
        return $this->enabledParents()->first();
    }


    public function getPhoneFormatedAttribute()
    {
        return $this->phone ? preg_replace(config('site.phone.get.pattern'), config('site.phone.get.replacement'), $this->phone) : null;
    }

    public function esbServiceRequests()
    {
        return $this->hasMany(
            EsbUserRequest::class,
            'recipient_id',
            'id'
        )
            ->orderByDesc('esb_user_requests.created_at');
    }

    public function esbRequests()
    {
        return $this->hasMany(
            EsbUserRequest::class,
            'esb_user_id',
            'id'
        )
            ->orderByDesc('esb_user_requests.created_at');
    }
    public function esbUsers()
    {
        return User::whereHas('esbServices', function($q) {$q->where('service_id',$this->id);})
            ->where('type_id',4)
            ->orWhere('created_by',$this->id);

    }
    public function esbProductLaunches()
    {
        return $this->hasMany(
            EsbProductLaunch::class,
            'service_id',
            'id'
        )
            ->orderByDesc('esb_product_launches.created_at');
    }

    public function esbProductMaintenances()
    {
        return $this->hasMany(
            EsbProductMaintenance::class,
            'service_id',
            'id'
        )
            ->orderByDesc('esb_product_maintenances.created_at');
    }

    public function esbProducts()
    {
        return $this->hasMany(
            EsbUserProduct::class,
            'user_id','id'
        )
            ->where('enabled',1)
            ->orderByDesc('esb_user_products.created_at');
    }

    public function esbRepairs()
    {
        return $this->hasMany(
            EsbRepair::class,
            'service_id','id'
        )
            ->orderByDesc('esb_repairs.created_at');
    }

    public function esbServices()
    {
        return $this->belongsToMany(
            User::class,
            'esb_user_services',
            'esb_user_id',
            'service_id'
        )->withPivot('enabled','accepted')
            ->orderByDesc('esb_user_services.created_at');
    }
    public function esbServiceRelations()
    {
        return $this->hasMany(
            EsbUserService::class,
            'esb_user_id'
        )->orderByDesc('esb_user_services.created_at');
    }

    public function esbCatalogPrices()
    {
        return $this->hasMany(
            EsbCatalogServicePrice::class,
            'company_id',
            'id'
        );
    }
    public function esbCatalogActualPrices()
    {
        return $this->esbCatalogPrices()->where('enabled',1)->whereNotNull('price')
            ->whereHas('service')
            ->with('service');
    }

    public function esbRetailOrders()
    {
        return $this->hasMany(
            RetailOrder::class,
            'esb_user_id'
        )->orderByDesc('retail_orders.created_at');
    }



    public function esbContractTypes()
    {
        return $this->hasMany(EsbContractType::class, 'user_id');
    }

    public function webinarsVisits()
    {
        return $this->belongsToMany(
            Webinar::class,
            'webinar_users',
            'user_id',
            'webinar_id'
        )->whereNotNull('webinar_users.visit');
    }
    public function webinarVisit($webinar_id)
    {
        return $this->belongsToMany(
            Webinar::class,
            'webinar_users',
            'user_id',
            'webinar_id'
        )->where('webinar_users.webinar_id',$webinar_id)->whereNotNull('webinar_users.visit');
    }

    public function userRelationChilds()
    {
        return $this->hasMany(UserRelation::class,'parent_id');
    }

    public function userRelationParents()
    {
        return $this->hasMany(UserRelation::class,'child_id')->orderByDesc('user_relations.created_at');

    }
    public function userRelationEnabledParents()
    {
        return $this->hasMany(UserRelation::class,'child_id')
            ->where('user_relations.accepted','1')
            ->where('user_relations.enabled','1')
            ->orderByDesc('user_relations.created_at');
    }

    public function company(){
        if(in_array($this->type_id,[1,2]) or is_null($this->type_id)){
            return $this;
        } else{
            return $this->enabledParents()->first();
        }

    }


    public function userSubordinates()
    {
        return $this->hasMany(UserSubordinate::class,'user_id');
    }


    public function subordinates()
    {
        return $this->belongsToMany(
            User::class,
            'user_subordinates',
            'user_id',
            'subordinate_id'
        );
    }

    public function chiefs()
    {
        return $this->belongsToMany(
            User::class,
            'user_subordinates',
            'subordinate_id',
            'user_id'
        );
    }
    public function getChiefAttribute()
    {
        $chief=$this->chiefs()->orderByDesc('created_at')->first();
        if(!empty($chief)) {
            return $this->chiefs()->orderByDesc('created_at')->first();
        } else {
            return User::find(1);
        }

    }

    public function getShowMapServiceAttribute()
    {

        $errors=array();

        if(!$this->addresses->where('is_service',1)->count()) {$errors[]='address_no_asc_checkbox';}
        if(!$this->addresses->where('is_service',1)->where('type_id',2)->where('approved',1)->count()){$errors[]='address_not_approved';}
        if(!$this->addresses->where('is_service',1)->where('show_ferroli',1)->count()){$errors[]='address_no_show_checkbox';}
        if(!$this->authorizations->where('status_id',2)->where('role_id',3)->count()){$errors[]='no_authorizations';}
        if(!$this->display){$errors[]='user_no_display_checkbox';}
        if(!$this->active){$errors[]='user_no_active_checkbox';}
        if(in_array($this->type_id,[3])){$errors[]='user_is_fl';}
        if(!$this->hasRole('asc')){$errors[]='user_no_asc_role';}
        if(!empty($errors)){
            return $errors;
        } else {
            return 1;
        }

    }

    public function getShowMapDealerAttribute()
    {
        $errors=array();

        if(!$this->addresses->where('is_shop',1)->count()) {$errors[]='address_no_shop_checkbox';}
        if(!$this->addresses->where('is_shop',1)->where('type_id',2)->where('approved',1)->count()){$errors[]='address_not_approved';}
        if(!$this->addresses->where('is_shop',1)->where('show_ferroli',1)->count()){$errors[]='address_no_show_checkbox';}
        if(!$this->authorizations->where('status_id',2)->where('role_id',4)->count()){$errors[]='no_authorizations';}
        if(!$this->display){$errors[]='user_no_display_checkbox';}
        if(!$this->active){$errors[]='user_no_active_checkbox';}
        if(in_array($this->type_id,[3])){$errors[]='user_is_fl';}
        if(!$this->hasRole('dealer')){$errors[]='user_no_dealer_role';}
        if(!empty($errors)){
            return $errors;
        } else {
            return 1;
        }
    }



    public function UserFlRoleRequests()
    {
        return $this->hasMany(UserFlRoleRequest::class,'user_id');
    }

    public function userCertificates()
    {
        return $this->hasMany(Certificate::class);
    }
    /**
     * Ввод в эксплуатацию
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function launches()
    {
        return $this->hasMany(Launch::class);
    }

    /**
     * Отчеты по ремонту
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function repairs()
    {
        return $this->hasMany(Repair::class);
    }

    public function tenders()
    {
        return $this->hasMany(Tender::class,'manager_id');
    }
    public function distrTenders()
    {
        return $this->hasMany(Tender::class,'distributor_id');
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }


    public function crmSalesPlans()
    {
        return $this->hasMany(CrmSalesPlan::class, 'user_id');
    }
    public function crmSalesPlan($period_type_id,$period_num=null,$year=null)
    {
        if(!$year){$year=Carbon::now()->format('Y');}
        if($period_num) {
            return $this->crmSalesPlans()->where('period_type_id', $period_type_id)
                ->where('year', $year)->where('period_num', $period_num)->orderByDesc('created_at')->first();
        } else{
            return $this->crmSalesPlans()->where('period_type_id', $period_type_id)
                ->where('year', $year)->orderByDesc('created_at')->first();
        }
    }

    public function crmSalesPredicts()
    {
        return $this->hasMany(CrmSalesPredict::class, 'user_id');
    }

    public function crmSalesPredict($period_type_id,$period_num,$predict_type_id='all',$year=null)
    {
        if(!$year){$year=Carbon::now()->format('Y');}
        return $this->crmSalesPredicts()->where('period_type_id',$period_type_id)->where('period_num',$period_num)->where('year',$year)
            ->where('predict_type_id',$predict_type_id)->orderByDesc('created_at')->first();

    }

    public function result1cSales()
    {
        return Result1cSale::where(function ($query) {
            $query->whereIn('contragent_inn', $this->contragents()->pluck('inn')->toArray())
                ->orWhere(function ($q) {$q->where('partner_guid',$this->guid)->whereNotNull('partner_guid');});

        });



    }

    public function result1cSale($result_type, $year=null, $period_type=null, $period_num=null, $date_actual=null)
    {
        if(!$year) {$year=Carbon::now()->format('Y');}
        $result=collect();
        if($period_type) {
            if($period_type=='month') {
                $start = Carbon::createFromDate($year, $period_num)->startOfMonth()->format('Y-m-d');
                $end = Carbon::createFromDate($year, $period_num)->endOfMonth()->format('Y-m-d');

            } elseif($period_type=='year'){
                $start = Carbon::createFromDate($year)->startOfYear()->format('Y-m-d');
                $end = Carbon::createFromDate($year)->endOfYear()->format('Y-m-d');

            } elseif($period_type=='quarter'){
                $start = Carbon::createFromDate($year, $period_num)->firstOfQuarter->format('Y-m-d');
                $end = Carbon::createFromDate($year, $period_num)->lastOfQuarter->format('Y-m-d');
            }
            $values=$this->result1cSales()->where('period_type',$period_type)
                ->where('type_id',$result_type)
                ->where('period_date_start','>=',$start)
                ->where('period_date_end','<=',$end)
                ->orderByDesc('date_actual')->get();

            $date_actual=$values->first() ? $values->first()->date_actual : null;

            $value=$values->where('date_actual',$date_actual)->sum('value');

            $result->put('value',$value);
            $result->put('results',$values);
            $result->put('date_actual',$date_actual);

            return $result;

        } else {

            $values=$this->result1cSales()->where('type_id',$result_type)
                ->orderByDesc('date_actual')->get();
            $date_actual=$values->first() ? $values->first()->date_actual : Carbon::now()->format('Y-m-d');
            $value=$values->where('date_actual',$date_actual)->sum('value');

            $result->put('value',$value);
            $result->put('results',$values);
            $result->put('date_actual',$date_actual);
            return $result;
        }


    }

    public function crmSales1cPlans()
    {
        return CrmSales1cPlan::where(function ($query) {
            $query->whereIn('contragent_inn', $this->contragents()->pluck('inn')->toArray())
                ->orWhere(function ($q) {$q->where('partner_guid',$this->guid)->whereNotNull('partner_guid');});

        });



    }

    public function crmSales1cPlan($result_type, $year=null, $period_type=null, $period_num=null, $date_actual=null)
    {
        if(!$year) {$year=Carbon::now()->format('Y');}
        $result=collect();

        if($period_type) {

            if($period_type=='month') {
                $start = Carbon::createFromDate($year, $period_num)->startOfMonth()->format('Y-m-d');
                $end = Carbon::createFromDate($year, $period_num)->endOfMonth()->format('Y-m-d');

            } elseif($period_type=='year'){
                $start = Carbon::createFromDate($year)->startOfYear()->format('Y-m-d');
                $end = Carbon::createFromDate($year)->endOfYear()->format('Y-m-d');

            } elseif($period_type=='quarter'){
                $start = Carbon::createFromDate($year, $period_num)->firstOfQuarter->format('Y-m-d');
                $end = Carbon::createFromDate($year, $period_num)->lastOfQuarter->format('Y-m-d');
            } else{

            }
            $values=$this->crmSales1cPlans()->where('period_type',$period_type)
                ->where('type_id',$result_type)
                ->where(function ($q) use($start,$end,$year,$period_num) {
                    $q->where('period_date_start','>=',$start)
                        ->where('period_date_end','<=',$end)
                        ->orWhere( function ($q) use($year,$period_num) {
                            $q->where('period_num',$period_num)
                                ->whereNotNull('period_num')
                                ->where('year',$year)
                                ->whereNotNull('year');
                        }) ;
                })

                ->orderByDesc('date_actual')->get();

            $date_actual=$values->first() ? $values->first()->date_actual : null;

            $value=$values->where('date_actual',$date_actual)->sum('value');
            $result->put('value',$value);
            $result->put('results',$values);
            $result->put('date_actual',$date_actual);

            return $result;

        } else {

            $values=$this->crmSales1cPlans()->where('type_id',$result_type)
                ->orderByDesc('date_actual')->get();
            $date_actual=$values->first() ? $values->first()->date_actual : Carbon::now()->format('Y-m-d');
            $value=$values->where('date_actual',$date_actual)->sum('value');

            $result->put('value',$value);
            $result->put('results',$values);
            $result->put('date_actual',$date_actual);
            return $result;
        }


    }

    public function standOrders()
    {
        return $this->hasMany(StandOrder::class);
    }

    public function standDistrOrders()
    {
        return StandOrder::query()->whereIn('status_id',config('site.distr_stand_order_status'))->whereHas('warehouse_address', function ($query) {
            $query
                ->where('type_id', 6)
                ->where('addressable_id', $this->getAttribute('id'))
                ->where('addressable_type', DB::raw('"users"'));
        });
    }

    /**
     * Отчеты по монтажу
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mountings()
    {
        return $this->hasMany(Mounting::class);
    }

    public function launchReports()
    {
        return $this->hasMany(LaunchReport::class,'user_id');
    }

    public function retailSaleReports()
    {
        return $this->hasMany(RetailSaleReport::class);
    }

    public function getUserMotivationStatusAttribute()
    {
        $certEngineers = $this->engineers()->whereHas('certificates',function ($query) {$query->where('certificates.type_id', 2);})->get();
        $certUser = $this->certificates()->where('certificates.type_id', 2)->get();
        $old_mounting = collect();
        $start_date_mounting = Carbon::now()->subYears(90);
        foreach($this->mountings->sortByDesc('created_at') as $mounting)
        {
            if(!empty($old_mounting->created_at)) {
                if($mounting->created_at->diffInDays($old_mounting->created_at, false) > 90) {
                    $start_date_mounting = $mounting->created_at->subDays(1);
                }
            }
            $old_mounting = $mounting;

        }


        $mountingsQr = $this->mountings->where('created_at','>',$start_date_mounting)->where('status_id','2')->count();


        if($mountingsQr >= 3 && $mountingsQr < 10 && ($certEngineers->count() > 0 || $certUser->count() > 0)) {
            return 'profi';
        } elseif($mountingsQr >= 10 && ($certEngineers->count() > 0 || $certUser->count() > 0)) {
            return 'super';
        } elseif($certEngineers->count() > 0 || $certUser->count() > 0)
        {
            return 'basic';
        } else {
            return 'start';
        }

        /*
        if($mountingsQr > 3 && $certEngineers->count() > 0) {
        return 'basic';
        } elseif($mountingsQr > 10 && $certEngineers->count() > 0) {
        return 'basic';
        } elseif($certEngineers->count() > 0)
        {
        return 'basic';
        } else {
            return 'basic';
        }
    */

    }

    public function getuserMotivationSaleStatusAttribute()
    {
        $certUser = $this->certificates()->where('certificates.type_id', 3)->get();
        if($certUser->count() > 0){
            return 'basic';
        } else {
            return 'start';
        }

    }

    public function getHasContractAttribute()
    {
        return $this->contragents()->where('contract','!=','')->count()>0 ? true : false;

    }

    public function getPublicNameAttribute()
    {
        return $this->name_for_site ? $this->name_for_site : $this->name;

    }


    public function getNameShortAttribute()
    {
        return $this->short_name ? $this->short_name : mb_substr($this->public_name,0,22);

    }

    public function getNameFiltredAttribute () {
        if($this->esbRequests()->where('status_id','!=',7)->where('recipient_id',auth()->user()->id)->exists()
            || $this->esbServices()->wherePivot('enabled',1)->get()->contains(auth()->user()->id)
            || auth()->user()->hasRole(config('site.supervisor_roles'),[])){
            return $this->name;
        } else {
            return $this->first_name .' ' .mb_substr($this->last_name,0,1);
        }

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function certificates()
    {
        //return $this->hasManyThrough(Certificate::class, Engineer::class);
        return $this->hasMany(Certificate::class);
    }

    public function mountingCertificates()
    {
        return $this->certificates()->where('type_id', 2)->orderByDesc('certificates.created_at');
    }

    public function serviceCertificates()
    {
        return $this->certificates()->where('type_id', 1)->orderByDesc('certificates.created_at');
    }

    public function certificatesShared()
    {
        return Certificate::whereHas('user', function ($q) {
            $q->whereHas('userRelationEnabledParents', function ($q) {
                $q->where('parent_id',$this->id);
            });
        });
    }

    public function saleCertificates()
    {
        return $this->certificates()->where('type_id', 3)->orderByDesc('certificates.created_at');
    }

    /**
     * Файлы
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    /**
     * Отправленные сообщения
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function outbox()
    {
        return $this->hasMany(Message::class, 'user_id')->where('personal', 0);
    }
    public function commentmessage()
    {
        return $this->hasMany(Message::class, 'user_id')->where('personal', 1);
    }

    /**
     * Полученные сообщения
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inbox()
    {
        return $this->hasMany(Message::class, 'receiver_id')->where('personal', 0)->where('readed', 0);
    }

    /**
     * Сообщения
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function messages():MorphMany
    {
        return $this->morphMany(Message::class, 'messagable');
    }
    /**
     * @return MorphMany
     */
    public function publicMessages()
    {
        return $this->messages()->where('personal', 0);
    }
    /**
     * Бонусы Digift
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function digiftBonuses()
    {
        return $this->hasMany(DigiftBonus::class);
    }

    /**
     * @throws DigiftException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function makeDigiftBonus()
    {
        $this->makeDigiftUser();
    }

    /**
     * @throws DigiftException
     * @throws GuzzleException
     */
    public function makeDigiftUser()
    {
        //dd($this->getDigiftUserData());
        if ($this->digiftUser()->doesntExist()) {
            $response = (new Digift())->createParticipant($digiftUserData = $this->getDigiftUserData())->request();
            if ($response->getStatusCode() === Response::HTTP_OK) {
                $body = json_decode($response->getBody(), true);
                if ($body['code'] == 0) {
                    $this->digiftUser()->create([
                        'id' => $digiftUserData['id'],
                        'accessToken' => $body['result']['accessToken'],
                        'tokenExpired' => $body['result']['tokenExpired'],
                        'fullUrlToRedirect' => $body['result']['fullUrlToRedirect'],
                    ]);
                } else {
                    throw new DigiftException($body['message']);
                }
            }
        }
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function digiftUser()
    {
        return $this->hasOne(DigiftUser::class, 'user_id');
    }

    public function getDigiftUserData()
    {
        $id = Str::uuid()->toString();
        $contacts = $this->contacts()->where('type_id', 1);
        if($this->phone){
            $login_phone=config('site.country_phone').$this->phone;
        } else {
            if ($contacts->exists()) {
                $phones = $contacts->first()->phones();
                if ($phones->exists()) {
                    $phone = $phones->first();
                    $login_phone = preg_replace('/\D/', '', $phone->country->phone . $phone->number);
                }
            } else {
                return[];
            }

        }

        if($this->type_id != 3) {
            list($lastName, $firstName, $middleName,) = explode(' ', $contacts->first()->getAttribute('name'));
        } else {
            if($this->last_name && $this->first_name && $this->middle_name ){
                $lastName = $this->last_name;
                $firstName = $this->first_name;
                $middleName = $this->middle_name;
            } else {
                list($lastName, $firstName, $middleName,) = explode(' ', $this->getAttribute('name'));
            }
        }

        return [
            'id' => $id,
            'email' => $this->getAttribute('email'),
            'phone' => $login_phone,
            'balance' => 0,
            'lastName' => $lastName,
            'firstName' => $firstName,
            'middleName' => $middleName,
        ];

    }




    /**
     * Check user online status
     *
     * @return bool
     */
    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->getAuthIdentifier());
    }

    public function hasVerified()
    {
        $this->verified = true;
        $this->verify_token = null;
        $this->save();
    }

    function path()
    {
        return 'users';
    }

    function lang()
    {
        return 'user';
    }

    /**
     * @return string
     */
    function messageSubject()
    {
        return trans('site::message.message');
    }

    /**
     * @return \Illuminate\Routing\Route
     */
    function messageRoute()
    {
        return route((auth()->user()->admin == 1 ? 'admin.users.show' : 'messages.index'), $this);
    }

    /**
     * @return \Illuminate\Routing\Route
     */
    function messageMailRoute()
    {
        return route((auth()->user()->admin == 1 ? 'messages.index' : 'admin.users.show'), $this);
    }

    /**
     * @return \Illuminate\Routing\Route
     */
    function messageStoreRoute()
    {
        //return route('admin.users.message', $this);
        return route(((auth()->user()->admin == 1 || auth()->user()->hasRole('ferroli_user')) ? 'admin.users.message': 'home.message' ), $this);
    }


    /** @return User */
    function messageReceiver()
    {
        return $this->id == auth()->user()->getAuthIdentifier()
            ? User::query()->findOrFail(config('site.receiver_id'))
            : $this;
    }

    public function managerRegionsRepairs($year = null, $status_id = null, $paid = null) {

        $repairs=Repair::whereHas('user', function ($query) { $query->whereIn('region_id',$this->ferroliManagerRegions()->pluck('regions.id'));});
        if ($paid) {
            $repairs=$repairs->whereHas('act', function ($query) {$query->where('paid','1');});
        }
        if($year){
            $repairs=$repairs->where('created_at','>=',$year .'.01.01')->where('created_at','<=',$year .'.12.31');
        }
        if ($status_id) {
            if(is_array($status_id)){
                $repairs=$repairs->whereIn('status_id',$status_id);
            } else {
                $repairs=$repairs->where('status_id',$status_id);
            }
        }
        return $repairs;

    }
    public function managerRegionsToDayRepairs($year, $date_to, $status_id = null, $paid = null) {

        $repairs=Repair::whereHas('user', function ($query) { $query->whereIn('region_id',$this->ferroliManagerRegions()->pluck('regions.id'));});
        if ($paid) {
            $repairs=$repairs->whereHas('act', function ($query) {$query->where('paid','1');});
        }
        if($date_to){
            $repairs=$repairs->where('created_at','>=',$year .'.01.01')->where('created_at','<=',Carbon::parse($date_to)->format('Y.m.d'));
        }
        if ($status_id) {
            if(is_array($status_id)){
                $repairs=$repairs->whereIn('status_id',$status_id);
            } else {
                $repairs=$repairs->where('status_id',$status_id);
            }
        }
        return $repairs;

    }

    public function managerRegionsToDayRepairsSum($year, $date_to) {

        $repairs_paid=Repair::where('created_at','>=',$year .'.01.01')->where('created_at','<=',Carbon::parse($date_to)->format('Y.m.d'))
            ->whereHas('act', function ($query) {$query->where('paid','1');})
            ->whereHas('user', function ($query) { $query->whereIn('region_id',$this->ferroliManagerRegions()->pluck('regions.id'));});

        return $repairs_paid->get()->sum('total_difficulty_cost')+$repairs_paid->get()->sum('total_distance_cost')+$repairs_paid->get()->sum('total_cost_parts');

    }
    public function managerRegionsToDayRepairsSumDiff( $date_from, $date_to) {

        $repairs_paid=Repair::where('created_at','>=',Carbon::parse($date_to)->format('Y.m.d'))->where('created_at','<=',Carbon::parse($date_to)->format('Y.m.d'))
            ->whereHas('act', function ($query) {$query->where('paid','1');})
            ->whereHas('user', function ($query) { $query->whereIn('region_id',$this->ferroliManagerRegions()->pluck('regions.id'));});

        return $repairs_paid->get()->sum('total_difficulty_cost')+$repairs_paid->get()->sum('total_distance_cost')+$repairs_paid->get()->sum('total_cost_parts');

    }



    public function managerRegionsStorehousesCsc() {

        return Storehouse::whereHas('user', function ($q) {
            $q->whereIn('region_id',$this->ferroliManagerRegions()->pluck('regions.id'))
                ->whereHas('roles', function ($q) {$q->where('name','csc');});
        });

    }

    public function resultCountTypeToDate($type_id, $date)
    {
        $result=Result::where('type_id',$type_id)->where('date_actual',$date)->where('object_id',$this->id)->where('objectable','region')->first();
        return empty($result) ? 0 : $result->value_int;
    }

    public function resultSumNotifiRegionsTypeToDate($type_id, $date)
    {
        $res_val = 0;

        foreach($this->ferroliManagerRegions as $region) {
            $result=Result::where('type_id',$type_id)->where('date_actual',$date)->where('object_id',$region->id)->where('objectable','region')->first();
            if(!empty($result)){ $res_val=$res_val+$result->value_int;}

        }
        return $res_val;
    }


    public function prereg()
    {
        return $this->hasMany(UserPrereg::class,'user_id');
    }

    public function getHasRoleRequestAttribute(){
        foreach($this->userRelationChilds as $userRelationChild) {
            if($userRelationChild->child->UserFlRoleRequests()->where('accepted','0')->where('decline','0')->count()>0) { return true; }
        }
        return false;

    }

    public function getContractIdAttribute(){
        if($this->hasRole('asc') && !empty($this->contragents->where('contract','<>','')->first()->id)) {
            return $this->contragents->where('contract','<>','')->first()->id;
        }
        return null;

    }

    public function distributorSaleLogs()
    {
        return $this->hasMany(DistributorSaleLog::class, 'user_id')->latest();
    }


    public function userVars()
    {
        return $this->hasMany(UserVar::class,'user_id');
    }
    public function userVarValue($varName)
    {
        $var=$this->userVars()->where('variable_name',$varName)->first();
        if($varName && $var) {
            return $var->variable_value;
        } else return null;
    }





}
