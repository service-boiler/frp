<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{

	protected $table = 'regions';

    public $timestamps = false;
    public $incrementing = false;

	protected $fillable = [
        'name','notification_address','italy_district_id'
    ];
	
    /**
     * Many-to-Many relations with address model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function warehouses()
    {
        return $this->belongsToMany(
            Address::class,
            'address_region',
            'region_id',
            'address_id');
    }

    /**
     * Адреса
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class, 'region_id');
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'region_id');
    }
    
    public function missions()
    {
        return $this->hasMany(Mission::class, 'region_id');
    }

    /**
     * Мероприятия
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'region_id');
    }

    /**
     * Страна
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
	
    /**
     * Округ IT
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function italy_district()
    {
        return $this->belongsTo(RegionItalyDistrict::class);
    }
	
    /**
     * Заявки
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members()
    {
        return $this->hasMany(Member::class, 'region_id');
    }
    public function user_preregs()
    {
        return $this->hasMany(UserPrereg::class, 'region_id');
    }
    public function tenders()
    {
        return $this->hasMany(Tender::class, 'region_id');
    }
    public function manager()
    {
        return $this->hasOne(User::class,'email','notification_address');
    }

    /**
     * Many-to-Many relations with role model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */ 

    public function userAddresses()
    {
        return $this->belongsToMany(
            Address::class,
            'address_region',
            'region_id',
            'address_id'
        );
    }
    
    public function repairs($year = null, $status_id = null)
    {
        $repairs=Repair::whereHas('user', function ($query){$query->where('region_id',$this->id);});
        
        
        
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
    
    public function engeneers()
    {
        return User::whereHas('roles', function ($query){$query->whereIn('name', ['service_fl']);})
                    ->where('active','1')
                    ->where('region_id',$this->id);
    }
    public function engeneers_attached()
    {
        return $this->engeneers()->whereHas('parents', function ($query) {
                                    $query->whereHas('roles', function ($query) 
                                                                        {$query->whereIn('name', ['asc','csc']);});});
    }
    public function engeneers_cert()
    {
        return $this->engeneers_attached()->whereHas('certificates', function ($query) {
                                                            $query->where('type_id','1');
                                                            });
    }
    
    
    
    public function users()
    {
        return  User::where('region_id',$this->id);
    }
    
    public function ascs()
    {
        return  $this->users()->whereHas('roles', function ($query){$query->whereIn('name', ['asc','csc']);})->where('active','1');
    }
    public function cscs()
    {
        return  $this->users()->whereHas('roles', function ($query){$query->whereIn('name', ['csc']);})->where('active','1');
    }
    
    public function storehouses()
    {
        return Storehouse::whereIn('user_id',$this->ascs->pluck('id'))->where('enabled',1);
    }
    
    public function storehouses_csc()
    {
        return Storehouse::whereIn('user_id',$this->cscs()->pluck('id'))->where('enabled',1);
    }
    
    
    
    public function result_count_type_to_date($type_id, $date)
    {
        $result=Result::where('type_id',$type_id)->where('date_actual',$date)->where('object_id',$this->id)->where('objectable','region')->first();
        return empty($result) ? 0 : $result->value_int;
    }

    
    public function getCountAscTodayAttribute()
    {
        return $this->ascs()->count();
        
    }

    
    public function getCountAscContractTodayAttribute()
    {
        return $this->ascs()->whereHas('contragents', function ($query) {
                                                                $query->where('contract','!=','');})->count();
        
    }
    
    public function getCountEngeneersTodayAttribute()
    {
        return $this->engeneers()->count();
        
    }
    
    public function getCountEngeneersAttachedTodayAttribute()
    {
        return $this->engeneers_attached()->count();
        
    }
    
    public function getCountEngeneersCertTodayAttribute()
    {
        return $this->engeneers_cert()->count();
        
    }

}
