<?php

namespace ServiceBoiler\Prf\Site\Support;

use Illuminate\Support\Collection;
use ServiceBoiler\Prf\Site\Contracts\Buyable;

class Item implements Buyable
{
    /**
     * @var Item
     */
    protected $product_id;
    protected $sku;
    protected $name;
    protected $type;
    protected $unit;
    protected $price;
    protected $format;
    protected $currency_id;
    protected $url;
    protected $image;
    protected $availability = false;
    protected $service = false;
    protected $quantity = 0;
    protected $storehouse_addresses = [];
    protected $group_type_id;
    protected $group_type_name;
    protected $group_type_icon;


    public function __construct(array $item)
    {

        $this->properties = new Collection();

        foreach ($item as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }

        return null;
    }

    /**
     * @return array
     */
    public function toArray()
    {

        return [
            'product_id'  => $this->product_id,
            'quantity'    => $this->quantity,
            'price'       => is_null($this->price) ? 0 : $this->price,
            'currency_id' => $this->currency_id,
        ];

    }

    /**
     * @return string
     */
    public function url()
    {
        if (config('cart.url') === true) {
            if ($this->hasUrl()) {
                return $this->url;
            }
        }

        return 'javascript:void(0);';
    }

    /**
     * @return bool
     */
    public function hasUrl()
    {
        return !is_null($this->url);
    }

    /**
     * @return bool
     */
    public function hasUnit()
    {
        return !is_null($this->unit);
    }


    /**
     * @return bool
     */
    public function hasType()
    {
        return !is_null($this->type);
    }

    /**
     * @return bool
     */
    public function hasImage()
    {
        return !is_null($this->image);
    }

    /**
     * @return bool
     */
    public function hasService()
    {
        return !is_null($this->service);
    }

    public function hasSku()
    {
        return !is_null($this->sku);
    }
    public function hasGroupType()
    {
        return !is_null($this->group_type_id);
    }


    public function hasStorehouseAddresses()
    {
        return !empty($this->storehouse_addresses);
    }

    public function setQuantity($quantity = 1)
    {
        $this->quantity = $quantity;
    }

    public function updateQuantity($quantity = 1)
    {
        $this->quantity += $quantity;
    }

    function quantity()
    {
        return $this->quantity;
    }

    function subtotal()
    {
        return $this->quantity * $this->price;
    }

    function weight()
    {
        return $this->quantity * $this->weight;
    }
}