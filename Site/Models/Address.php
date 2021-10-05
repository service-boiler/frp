<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Concerns\AttachRegions;
use ServiceBoiler\Prf\Site\Http\Requests\AddressRequest;
use ServiceBoiler\Prf\Site\Contracts\Imageable;
use Site;

class Address extends Model implements Imageable
{

    use AttachRegions;

    protected $fillable = [
        'type_id', 'country_id', 'region_id','main',
        'locality', 'street', 'building',
        'apartment', 'postal', 'name','description',
        'show_ferroli', 'show_lamborghini','show_market_ru','approved',
        'is_shop', 'is_service', 'is_eshop', 'is_mounter',
        'sort_order', 'email', 'web', 'storehouse_id',
    ];

    protected $casts = [
        'type_id'          => 'integer',
        'country_id'       => 'integer',
        'storehouse_id'    => 'integer',
        'region_id'        => 'string',
        'locality'         => 'string',
        'street'           => 'string',
        'name'             => 'string',
        'description'      => 'text',
        'sort_order'       => 'integer',
        'email'            => 'string',
        'web'              => 'string',
        'show_ferroli'     => 'boolean',
        'show_lamborghini' => 'boolean',
        'show_market_ru' 	=> 'boolean',
		  'approved'			=> 'boolean',
        'is_shop'          => 'boolean',
        'is_service'       => 'boolean',
        'is_eshop'         => 'boolean',
        'is_mounter'       => 'boolean',
    ];

    /**
     * @var string
     */
    protected $table = 'addresses';

    public static function boot()
    {
        parent::boot();

        static::saving(function (Address $address) {
            $httpClient = new \Http\Adapter\Guzzle6\Client();
            $provider = new \Geocoder\Provider\Yandex\Yandex($httpClient, null, env('YANDEX_GEOCODER_API_KEY'));
            $geocoder = new \Geocoder\StatefulGeocoder($provider, 'ru');
            $result = [];
            $result[] = $address->country->name;
            $result[] = $address->region->name;
            $result[] = $address->locality;
            $result[] = $address->street;
            $result[] = $address->building;
            $result[] = $address->apartment;
            $full = preg_replace('/(,\s)+$/', '', implode(', ', $result));
            $address->full = trim($full);
            $result = $geocoder->geocodeQuery(\Geocoder\Query\GeocodeQuery::create($full));
            if (!$result->isEmpty()) {
                $geocode = $result->first();
                $address->geo = implode(',', array_reverse($geocode->getCoordinates()->toArray()));
                //$formatter = new \Geocoder\Formatter\StringFormatter();
                //$name = $formatter->format($geocode, '%A1, %A2, %A3, %L, %D %S, %n');
                //$address->full = preg_replace(['/\s,/', '/\s+/'], ' ', $full);
            }

        });
    }

	 
    public function detachImages()
    {
        foreach ($this->images as $image) {
            $image->imageable_id = null;
            $image->imageable_type = null;
            $image->save();
        }
    }
	 
	     public function image()
    {
        return $this->images()->firstOrNew([]);
    }
	 
	     public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
	 
	 
    /**
     * Тип адреса
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(AddressType::class);
    }


    public function lat()
    {
        list($lat, $lon) = explode(',', $this->geo);

        return (float)$lat;
    }

    public function lon()
    {
        list($lat, $lon) = explode(',', $this->geo);

        return (float)$lon;
    }

    /**
     * Страна местонахождения
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function canSendMail()
    {
        return !is_null($this->getAttribute('email')) && Unsubscribe::where('email', $this->getAttribute('email'))->doesntExist();
    }


    /**
     * Склад
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function storehouse()
    {
        return $this->belongsTo(Storehouse::class);
    }
    public function esbProducts()
    {
        return $this->hasMany(EsbUserProduct::class, 'address_id');
    }

    /**
     * Get all of the owning addressable models.
     */
    public function addressable()
    {
        return $this->morphTo();
    }

    /**
     * Телефоны
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function phones()
    {
        return $this->morphMany(Phone::class, 'phoneable');
    }

    /**
     * Many-to-Many relations with region model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function regions()
    {
        return $this->belongsToMany(
            Region::class,
            'address_region',
            'address_id',
            'region_id');
    }

    /**
     * Many-to-Many relations with region model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function product_group_types()
    {
        return $this->belongsToMany(
            ProductGroupType::class,
            'address_product_group_type',
            'address_id',
            'type_id');
    }

    /**
     * Пользователи
     *
     * Для выборки на карту и списки адресов
    */     
    
    public function users()
    {
        return $this->hasMany(User::class, 'id', 'addressable_id')->where('addressable_type', 'users');
    }

	 public function user()
	{
		return $this->belongsTo(User::class, 'addressable_id', 'id');
	}

	 
    /**
     * Контрагнеты
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contragents()
    {
        return $this->hasMany(Contragent::class, 'id', 'addressable_id')->where('addressable_type', 'contragents');
    }

    public function hasEmail()
    {
        return !is_null($this->getAttribute('email'));
    }

    public function getCanEditRegionsAttribute()
    {
        return in_array($this->getAttribute('type_id'), [5, 6]);
    }

    /**
     * Регион
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
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

    /**
     * Заявки на монтаж
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mounters()
    {
        return $this->hasMany(Mounter::class, 'user_address_id');
    }
	 
	   /**
     * Заявки на покупку
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function retailorders()
    {
        return $this->hasMany(RetailOrder::class, 'user_address_id');
    }
    
    
       
    public function getCardWarningsAttribute()
	{  
      
       $errors=array();
       
       if($this->type_id==6 && !$this->product_group_types()->where('id',2)->exists()) {$errors[]='address_no_zip_checkbox';} 
        
        if(!empty($errors)){
            return $errors;
        } else {
        return 0;
        }
    
    } 

}
