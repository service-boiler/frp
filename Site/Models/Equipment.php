<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use ServiceBoiler\Prf\Site\Concerns\Sortable;
use ServiceBoiler\Prf\Site\Contracts\Imageable;
use ServiceBoiler\Prf\Site\Models\Equipment;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Models\ProductSpec;
use ServiceBoiler\Prf\Site\Models\Price;


class Equipment extends Model implements Imageable
{

    use Sortable;

    protected $table = 'equipments';

    protected $fillable = [
        'name', 'annotation', 'annotation_by', 'menu_annotation', 'description',
        'h1', 'title', 'metadescription',
        'specification', 'catalog_id', 'sort_order','sort_order_menu',
        'enabled', 'show_ferroli', 'show_lamborghini',
        'mounter_enabled','show_market_ru'
    ];

    protected $casts = [

        'name'             => 'string',
        'annotation'       => 'string',
        'annotation_by'       => 'string',
        'menu_annotation'  => 'string',
        'description'      => 'string',
        'h1'               => 'string',
        'title'            => 'string',
        'metadescription'  => 'string',
        'specification'    => 'string',
        'catalog_id'       => 'integer',
        'sort_order'       => 'integer',
        'sort_order_menu'  => 'integer',
        'enabled'          => 'boolean',
        'show_ferroli'     => 'boolean',
        'show_lamborghini' => 'boolean',
        'show_market_ru'   => 'boolean',
        'mounter_enabled'  => 'boolean',
    ];

    public function detachImages()
    {
        foreach ($this->images as $image) {
            $image->imageable_id = null;
            $image->imageable_type = null;
            $image->save();
        }
    }

    /**
     * Каталог
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function catalog()
    {
        return $this->belongsTo(Catalog::class);
    }

    /**
     * @return Model
     */
    public function image()
    {
        return $this->images()->firstOrNew([]);
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
     * Отзывы
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Оборудование
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
    public function availableProducts(){
    	return $this->products()->where('enabled', 1)->where(config('site.check_field'), 1)->orderBy('name');
    }

	public function prices()
	{	
		return $this->hasManyThrough(Price::class, Product::class)
		->whereIn('prices.type_id', [
		config('site.defaults.guest.price_type_id')
		,config('site.defaults.guest.price_promo_eur_type_id')
		,config('site.defaults.guest.price_promo_rub_type_id')
		]);
	}
	
	public function getPriceAttribute()
	{
		return $this
			->getPrice()->orderBy('prices.price', 'desc')
			->firstOrNew([]);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	private function getPrice()
	{

		return $this
			->prices()->orderBy('currency_id')->orderBy('price');
	}
    
    public function getSpecsAttribute()
    {
    $equipment_id = $this->id;
    return ProductSpec::whereHas('products', function ($query) use($equipment_id) {
                                            $query->where('equipment_id', $equipment_id);
                                        })->orderBy('sort_order')->get();
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

    public function canDelete()
    {
        return $this->products->isEmpty();
    }

}
