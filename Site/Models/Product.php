<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use ServiceBoiler\Prf\Site\Concerns\AttachAnalogs;
use ServiceBoiler\Prf\Site\Concerns\AttachDetails;
use ServiceBoiler\Prf\Site\Concerns\AttachRelations;
use ServiceBoiler\Prf\Site\Contracts\Imageable;
use ServiceBoiler\Prf\Site\Facades\Site;

class Product extends Model implements Imageable
{

	use AttachAnalogs, AttachDetails, AttachRelations;
	/**
	 * @var bool
	 */
	public $incrementing = false;

	/**
	 * @var array
	 */
	protected $fillable = [
		'name', 'sku', 'old_sku', 'enabled',
		'show_ferroli', 'show_lamborghini', 'forsale', 'for_preorder', 'for_stand',
		'warranty', 'service', 'not_available', 'description',
		'h1', 'title', 'metadescription',
		'specification', 'equipment_id', 'type_id','sort_order',
	];

	protected $casts = [

		'name' => 'string',
		'sku' => 'string',
		'old_sku' => 'string',
		'h1' => 'string',
		'title' => 'string',
		'metadescription' => 'string',
		'specification' => 'string',
		'enabled' => 'boolean',
		'show_ferroli' => 'boolean',
		'show_lamborghini' => 'boolean',
		'warranty' => 'boolean',
		'service' => 'boolean',
		'forsale' => 'boolean',
		'for_preorder' => 'boolean',
		'equipment_id' => 'integer',
		'type_id' => 'integer',
		'sort_order' => 'integer',
	];

	/**
	 * Тип товара
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function type()
	{
		return $this->belongsTo(ProductType::class);
	}

	/**
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param null $product_id
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeMounted($query, $product_id = null)
	{
		$query
			->whereNotNull('sku')
			->where('enabled', 1)
			->where('show_ferroli', 1)
			->wherenotin('equipment_id', [53,147])
			->wherein('type_id', [1,3])
			->with('mounting_bonus');
		if (!is_null($product_id)) {
			$query->where('id', $product_id);
		}

		return $query;
	}
	
    public function scopeRetailSaled($query, $product_id = null)
	{
		$query
			->whereNotNull('sku')
			->where('enabled', 1)
			->where('show_ferroli', 1)
			->wherenotin('equipment_id', [53,147])
			->wherein('type_id', [1,3])
			->with('product_retail_sale_bonus');
		if (!is_null($product_id)) {
			$query->where('id', $product_id);
		}

		return $query;
	}
    public function scopeRetailSaleBonusabled($query, $product_id = null)
	{
		$query
			->whereNotNull('sku')
			->where('enabled', 1)
			->where('show_ferroli', 1)
			->wherenotin('equipment_id', [31,53,147])
			->wherein('type_id', [1,3])
            ->whereHas('product_retail_sale_bonus')
			->with('product_retail_sale_bonus');
		if (!is_null($product_id)) {
			$query->where('id', $product_id);
		}

		return $query;
	}

	/**
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeMounter($query)
	{
		$query
			->where(config('site.check_field'), 1)
			->where('enabled', 1);

		return $query;
	}

	/**
	 * Производитель
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function brand()
	{
		return $this->belongsTo(Brand::class)->withDefault(function ($brand) {
			$brand->name = '';
		});
	}

	public function getFullNameAttribute()
	{
		$name = [];
		if (mb_strlen($this->getAttribute('name'), 'UTF-8') > 0) {
			$name[] = $this->getAttribute('name');
		}
		if (mb_strlen($this->getAttribute('sku'), 'UTF-8') > 0) {
			$name[] = "({$this->getAttribute('sku')})";
		}

		return !empty($name) ? implode(' ', $name) : $this->getAttribute('id');
	}

	public function name()
	{
		$name = [];
		if (mb_strlen($this->sku, 'UTF-8') > 0) {
			$name[] = "{$this->sku}";
		}
		if (mb_strlen($this->name, 'UTF-8') > 0) {
			$name[] = $this->name;
		}


		return !empty($name) ? implode(' • ', $name) : $this->id;
	}

	public function getNameAttribute($name)
	{
		$name = str_replace('&rsquo;', "'", $name);
		$name = str_replace('&rdquo;', '"', $name);
		$name = str_replace('&lt;', '<', $name);
		$name = str_replace('&gt;', '>', $name);
		$name = str_replace('&frasl;', '/', $name);
		$name = str_replace('&ndash;', '-', $name);

		return $name;
	}

	/**
	 * Модель
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function equipment()
	{
		return $this->belongsTo(Equipment::class);
	}

    public function specs()
	{
		return $this->belongsToMany(ProductSpec::class, 'product_spec_relations')->withPivot('spec_value')->orderBy('sort_order');
	}
    public function specRelations()
	{
		return $this->hasMany(ProductSpecRelation::class);
	}
    
 
	public function toCart()
	{   if(!empty($this->pricepromo) && $this->pricepromo->value<>0 && $this->pricepromo->value != $this->price->value){
			$price= $this->pricepromo->value;
			}
		else {
			$price= $this->price->value;
		}
        
		return [
			'product_id' => $this->id,
			'sku' => $this->sku,
			'name' => $this->name,
			'type' => $this->type->name,
			'unit' => $this->unit,
			'price' => $this->hasPrice ? $price : null,
			'format' => Site::format($price),
			'currency_id' => Site::currency()->id,
			'url' => route('products.show', $this),
			'image' => $this->image()->src(),
			'availability' => $this->quantity > 0,
			'service' => $this->service == 1,
			'group_type_id' => $this->group()->exists() ? $this->group->type_id : null,
			'group_type_name' => $this->group()->exists() ? $this->group->type->name : null,
			'group_type_icon' => $this->group()->exists() ? $this->group->type->icon : null,
			'storehouse_addresses' => $this->storehouseAddresses()->toArray(),
		];
	}

	/**
	 * @return Model|\Illuminate\Database\Eloquent\Relations\MorphMany
	 */
	public function image()
	{
		if ($this->images()->count() == 0) {
			return new Image([
				'src' => storage_path('app/public/images/products/noimage.png'),
				'storage' => 'products',
			]);
		}

		return $this->images()->first();
	}

	/**
	 * Изображения
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\morphMany
	 */
	public function images()
	{
		return $this->morphMany(Image::class, 'imageable');
	}

	/**
	 * Товарная группа 1С
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function group()
	{
		return $this->belongsTo(ProductGroup::class, 'group_id');
	} 
	
    public function revisionPartsNew()
	{
		return $this->hasMany(RevisionPart::class, 'part_old_id');
	}   
    
    public function revisionPartsOld()
	{
		return $this->belongsToMany(RevisionPart::class, 'part_new_id');
	}   

    /**
     * Отзывы
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

	/**
	 * Адреса оптовых складов, на которых есть товар
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function storehouseAddresses()
	{

		$storehouse_addresses = collect([]);
		if (auth()->check()) {
			/**
			 *  Адреса склады в зависимости от региона
			 *
			 *  @var array $warehouses
			 */
			$warehouses = auth()->user()->warehouses()->pluck('id')->toArray();
           
			foreach ($this->storehouse_products()
				         ->with('storehouse', 'storehouse.addresses')
				         ->whereHas('storehouse', function ($storehouse) {
					         $storehouse->where('enabled', 1);
				         })->get() as $storehouse_product) 
           {
				if(env('MIRROR_CONFIG')!='marketru') {
                foreach ($storehouse_product->storehouse->addresses()
                            ->when(auth()->user()->only_ferroli == 1 
                            || auth()->user()->hasRole(['админ','ferroli_user','supervisor'], [])
                            || !empty(auth()->user()->userRelationParents->where('enabled','1')->filter(function ($relation) {
                                        return $relation->parent->hasRole(config('site.ferroli_shouse_roles', []), false);
                                        })->all()) 
                            || auth()->user()->hasRole(config('site.ferroli_shouse_roles', []), false),
                            
						         function ($query) use ($warehouses) {
                                     return $query->whereIn('id', $warehouses);
						         },
                                 
						         function ($query) {
                                 
							         $query->whereHas('regions', function ($region) {
								         $region->where('regions.id', auth()->user()->region_id);
							         });
						         })
					         ->get() as $address) 
                {
					$storehouse_addresses->push([
						'id' => $address->id,
						'name' => $address->name,
						'quantity' => $storehouse_product->quantity,
						'updated_at' => $storehouse_product->updated_at,
					]);

				}
			} else {
            
                foreach ($storehouse_product->storehouse->addresses()
                             ->get() as $address) 
                {
					$storehouse_addresses->push([
						'id' => $address->id,
						'name' => $address->name,
						'quantity' => $storehouse_product->quantity,
						'updated_at' => $storehouse_product->updated_at,
					]);

				}
                        
            }
               
            }
		}
        
    	return $storehouse_addresses;
	}
	public function storehouseAllAddresses()
	{

		$storehouse_addresses = collect([]);
		if (auth()->check() && auth()->user()->hasRole(['админ','csc','gendistr','distr','ferroli_user','supervisor'], [])) {
			/**
			 *  Адреса склады в зависимости от региона
			 *
			 *  @var array $warehouses
			 */
			$warehouses = auth()->user()->warehousesAll()->pluck('id')->toArray();
           
			foreach ($this->storehouse_products()
				         ->with('storehouse', 'storehouse.addresses')
				         ->whereHas('storehouse', function ($storehouse) {
					         $storehouse->where('enabled', 1);
				         })->get() as $storehouse_product) 
           {
				
                
                foreach ($storehouse_product->storehouse->addresses()
                            ->when(auth()->user()->only_ferroli == 1 
                            || auth()->user()->hasRole(['админ','ferroli_user','supervisor'], [])
                            || !empty(auth()->user()->userRelationParents->where('enabled','1')->filter(function ($relation) {
                                        return $relation->parent->hasRole(config('site.ferroli_shouse_roles', []), false);
                                        })->all()) 
                            || auth()->user()->hasRole(config('site.ferroli_shouse_roles', []), false),
                            
						         function ($query) use ($warehouses) {
                                     return $query->whereIn('id', $warehouses);
						         },
                                 
						         function ($query) {
                                 
							         $query->whereHas('regions', function ($region) {
								         $region->where('regions.id', auth()->user()->region_id);
							         });
						         })
					         ->get() as $address) 
                {
					$storehouse_addresses->push([
						'id' => $address->id,
						'name' => $address->name,
						'quantity' => $storehouse_product->quantity,
						'updated_at' => $storehouse_product->updated_at,
					]);

				}
			
               
            }
		}
        
    	return $storehouse_addresses;
	}

	/**
	 * Товар на складах дистрибюторов
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function storehouse_products()
	{
		return $this->hasMany(StorehouseProduct::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\morphMany
	 */
	public function _images()
	{
		return $this->morphMany(Image::class, 'imageable');
	}

	/**
	 * Получить тип цены для отчета по ремонту
	 *
	 * @return \Illuminate\Config\Repository|mixed
	 */
	public function getRepairPriceTypeAttribute()
	{
		return Auth::guest() 
        ? config('site.defaults.guest.price_type_id') 
        : config('site.defaults.part.price_type_id', config('site.defaults.user.price_type_id'));
	}

    /**
	 * @return Model
	 */
	public function getRepairPriceAttribute()
	{
		return $this->getRepairPrice()->firstOrNew([]);
	}
	
    private function getRepairPrice()
	{   $repair_price_ratio = Auth::user()->repair_price_ratio;
		return $this->prices()
            ->where('type_id', '=', $this->repairPriceType)
            ->where('price', '<>', 0.00);
	}  
    
	public function getTenderPriceTypeAttribute()
	{
		return config('site.defaults.tender.price_type_id') ;
	}

    /**
	 * @return Model
	 */
	public function getTenderPriceAttribute()
	{
		return $this->getTenderPrice()->firstOrNew([]);
	}
	
    private function getTenderPrice()
	{   
		return $this->prices()
            ->where('type_id', '=', $this->tenderPriceType)
            ->where('price', '<>', 0.00);
	}    

    public function getStandOrderPriceTypeAttribute()
	{
		return Auth::guest() ? config('site.defaults.guest.price_type_id') : config('site.defaults.stand_item.price_type_id', config('site.defaults.user.price_type_id'));
	}
   

    public function getStandOrderPriceAttribute()
	{
		return $this->getStandOrderPrice()->firstOrNew([]);
	}

	private function getStandOrderPrice()
	{   
		return $this->prices()->where('type_id', '=', $this->standOrderPriceType)->where('price', '<>', 0.00);
	}    

    
    public function getRetailPriceAttribute()
	{
		return $this->getRetailPrice()->firstOrNew([]);
	}

	private function getRetailPrice()
	{   
		return $this->prices()->where('type_id', '=', config('site.defaults.guest.price_type_id'))->where('price', '<>', 0.00);
	}
    
    public function getRetailPriceEurAttribute()
	{
		return $this->getRetailEurPrice()->firstOrNew([]);
	}

	private function getRetailEurPrice()
	{   
		return $this->prices()->where('type_id', '=', config('site.defaults.guest.price_eur_type_id'))->where('price', '<>', 0.00);
	}
    
    public function getRetailPriceRubAttribute()
	{
		return $this->getRetailRubPrice()->firstOrNew([]);
	}

	private function getRetailRubPrice()
	{   
		return $this->prices()->where('type_id', '=', config('site.defaults.guest.price_rub_type_id'))->where('price', '<>', 0.00);
	}
    
    public function getOldPriceAttribute()
	{   
        $old_price = $this->getOldPrice()->firstOrNew([])->value;
        $sc_price = $this->getPrice()->firstOrNew([])->value;
		$ret_price = $this->getRetailPrice()->firstOrNew([])->value;
        
        if($ret_price)
        return $sc_price * $old_price / $ret_price;
        else
        return 0;
	}

	private function getOldPrice()
	{   
		return $this->prices()->where('type_id', '=', config('site.defaults.guest.price_old_eur_type_id'))->where('price', '<>', 0.00);
	}

	/**
	 * Цены
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function prices()
	{
		return $this->hasMany(Price::class);
	}

	/**
	 * Склады дистрибьютора, на которых есть данный товар
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
	 */
	public function storehouses()
	{
		return $this->belongsToMany(Storehouse::class, 'storehouse_products');
	}

	/**
	 * Заявки на монтаж
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function mounters()
	{
		return $this->hasMany(Mounter::class);
	}

	/**
	 * Премия за отчет по монтажу
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function mounting_bonus()
	{
		return $this->hasOne(MountingBonus::class);
	}
    
	public function product_retail_sale_bonus()
	{
		return $this->hasOne(ProductRetailSaleBonus::class);
	}

	/**
	 * @return \Illuminate\Config\Repository|mixed
	 */
    public function getPriceTypeAttribute()
	{
		return Auth::guest()
			? config('site.defaults.guest.price_type_id')
			: (
			(
			$user_price = Auth::user()->prices()->where('product_type_id', $this->type_id))->exists()
				? $user_price->first()->price_type_id
				: config('site.defaults.user.price_type_id')
			);
	}
    
    public function priceTypeUser(User $user)
	{
		 
        return $user->prices()->where('product_type_id', $this->type_id)->first()->price_type_id;

	}
    public function priceUser(User $user)
	{
		 
        return $this->prices()->where('type_id', '=', $this->priceTypeUser($user))->where('price', '<>', 0.00)->firstOrNew([]);

	}
   
   
    
    
    public function getPriceTypeOverlapAttribute()
	{   
        if(Auth::guest()) {
        
         return config('site.defaults.guest.price_rub_type_id');
        
        } else {
            $user_price = Auth::user()->prices()->where('product_type_id', $this->type_id);
            if(!empty($user_price)) {
                
                if(!empty($user_price->first()->priceType->overlapPrice)) {
                    
                    $priceType=$user_price->first()->priceType->overlapPrice->id;
                   
                } else {
                   return NULL;
                   
                }
                
            
            }
            else {
              return NULL;
            }
        }
        return $priceType;
        /* return Auth::guest()
			? config('site.defaults.guest.price_type_id')
			: (
			($user_price = Auth::user()->prices()->where('product_type_id', $this->type_id))->exists()
				? $user_price->first()->price_type_id
				: config('site.defaults.user.price_type_id')
			); */
	}

	/**
	 * @return Model
	 */
	public function getPriceAttribute()
	{   
        return $this
			->getPrice()
			->firstOrNew([]);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	private function getPrice()
	{
        
        if($this->prices()
            ->where('type_id', '=', config('site.defaults.guest.price_rub_type_id'))
            ->where('price', '<>', 0.00)->firstOrNew([])->value != 0 && in_array(env('MIRROR_CONFIG'),['marketru']))
        { 
        return $this->prices()->where('type_id', '=', config('site.defaults.guest.price_rub_type_id'))->where('price', '<>', 0.00);
        }
        elseif(!empty($this->PriceTypeOverlap)) { 
            
            if(!empty($this->prices()->where('type_id', '=', $this->PriceTypeOverlap)->where('price', '<>', 0.00)->first())){
            
                return $this->prices()->where('type_id', '=', $this->PriceTypeOverlap)->where('price', '<>', 0.00);
            
            } else {
                return $this->prices()->where('type_id', '=', $this->priceType)->where('price', '<>', 0.00);
                
             }
		//return $this->prices()->where('type_id', '=', $this->priceType)->where('price', '<>', 0.00);
        }
        else {
        return $this->prices()->where('type_id', '=', $this->priceType)->where('price', '<>', 0.00);
        }
	}

	public function getPricepromoAttribute()
	{
			return $this
			->getPricepromo()
			->firstOrNew([]);
	}
	private function getPricepromo()
	{	if($this->prices()->where('type_id', '=', config('site.defaults.guest.price_promo_rub_type_id'))->where('price', '<>', 0.00)->firstOrNew([])->value <> 0)
			return $this->prices()->where('type_id', '=', config('site.defaults.guest.price_promo_rub_type_id'))->where('price', '<>', 0.00);
		else
			return $this->prices()->where('type_id', '=', config('site.defaults.guest.price_promo_eur_type_id'))->where('price', '<>', 0.00);
	}
	/**
	 * @return bool
	 */
	public function getHasPriceAttribute()
	{
		return $this->getPrice()->exists();
	}

	/**
	 * @return bool
	 */
	public function getCanBuyAttribute()
	{

	    return $this->getAttribute('enabled') == 1 && $this->getAttribute('service') == 0;
	}

	/**
	 * Документация
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function datasheets()
	{
		return $this->belongsToMany(
			Datasheet::class,
			'datasheet_product',
			'product_id',
			'datasheet_id'
		);
	}

	/**
	 * Взрывные схемы
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function schemes()
	{
		return $this->belongsToMany(
			Scheme::class,
			'product_scheme',
			'product_id',
			'scheme_id'
		);
	}

	/**
	 * Позиции в заказе
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function order_items()
	{
		return $this->hasMany(OrderItem::class);
	}

	/**
	 * Серийные номера
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function serials()
	{
		return $this->hasMany(Serial::class);
	}

	/**
	 * Отчеты по ремонту
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function repairs()
	{
		return $this->hasMany(Repair::class, 'product_id');
	}
    
	public function distributorSales()
	{
		return $this->hasMany(DistributorSale::class, 'product_id');
	}
    
	public function tenders()
	{
		return $this->belongsToMany(
			Tender::class,
			'tender_products',
			'product_id',
			'tender_id');
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

	public function hasSku()
	{
		return !is_null($this->getAttribute('sku'));
	}
	
    public function showRetPrice()
	{
		if(in_array($this->group->type_id,['1','3'])
            || (Auth::user() && Auth::user()->hasPermission('zip_price'))) {
        
            return true;
        
        } else {
            return false;
        }
	}


	/**
	 * @param $image_id
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function detachImage($image_id)
	{
		Image::query()->findOrNew($image_id)->delete();

		return $this;
	}

	/**
	 * Товар является аналогом для
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
	 */
	public function back_analogs()
	{
		return $this->belongsToMany(
			Product::class,
			'analogs',
			'analog_id',
			'product_id');
	}

	public function hasEquipment()
	{
		return !is_null($this->getAttribute('equipment_id'));
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
	 */
	public function availableDetails()
	{
		return $this->details()->where('enabled', 1)->where(config('site.check_field'), 1);
	}

}
