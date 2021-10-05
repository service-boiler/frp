<?php

namespace ServiceBoiler\Prf\Site\Support;

use Illuminate\Support\Collection;
use ServiceBoiler\Prf\Site\Contracts\Buyable;
use ServiceBoiler\Prf\Site\Contracts\Cart As BaseCart;

/**
 * This class is the main entry point of cart
 *
 * @license MIT
 * @package ServiceBoiler\Prf\Site\Support
 */
class Cart implements BaseCart
{

    /**
     * Laravel application
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;
    /**
     *
     */
    protected $session;

    /** @var  Collection */
    protected $items;

    /**
     * Create a new cart instance.
     */
    public function __construct($app, $session)
    {
        $this->app = $app;
        $this->session = $session;

        if ($this->session->has('cart')) {
            $this->items = $this->session->get('cart');
        } else {
            $this->clear();
            //$this->items = new Collection();
            //$this->save();
        }
    }

    protected function save()
    {
        $this->session->put('cart', $this->items);
        $this->session->save();
    }

    public function clear()
    {
        $this->items = new Collection();
        $this->save();
    }

    /**
     * Add item to the cart
     *
     */
    public function add(array $item)
    {

        if ($this->has($item['product_id'])) {
            $this->get($item['product_id'])->updateQuantity($item['quantity']);
        } else {
            $this->items->put($item['product_id'], new Item($item));
        }

        $this->save();

    }


    /**
     * Check if the item exists in the cart
     *
     * @param  mixed $id
     * @return boolean
     */
    public function has($id)
    {
        return $this->items->has($id);
    }

    /**
     * Get cart item by identifier
     *
     * @param $id
     * @return bool|Item
     */
    public function get($id)
    {
        if ($this->has($id)) {
            return $this->items->get($id);
        }

        return false;
    }

    public function update(array $item)
    {
        $this->get($item['product_id'])->setQuantity($item['quantity']);

        $this->save();
    }

    /**
     * @param array $items
     * @return array
     */
    public function toArray(array $items = array())
    {
        return $this->items()->filter(function ($item, $key) use ($items) {
            return in_array($key, $items);
        })->map(function($item){
            return $item->toArray();
        })->toArray();

    }

    /**
     * Check if the cart is empty
     * @return bool
     */
    public function isEmpty()
    {
        return $this->items()->isEmpty();
    }

    /**
     * Get all cart items
     *
     * @return Collection
     */
    public function items()
    {
        return $this->items;
    }

    /**
     * Remove an item from the cart
     *
     * @param  mixed $id
     * @return void
     */
    public function remove($id)
    {
        $this->items->forget($id);
        $this->save();
    }

    /**
     * Get cart items count
     *
     * @return int
     */
    public function count()
    {
        return $this->items->count();
    }

    /**
     * @return int
     */
    function quantity()
    {
        $total = 0;

        foreach ($this->items as $item) {
            if ($item instanceof Buyable) {
                $total += $item->quantity();
            }
        }

        return $total;
    }

    /**
     * @return float
     */
    function total()
    {
        $total = 0.00;

        foreach ($this->items as $item) {
            if ($item instanceof Buyable) {
                $total += $item->subtotal();
            }
        }

        return $total;
    }

    function total_format(){
        return number_format($this->total(), config('cart.decimals'), config('cart.decimalPoint'), config('cart.thousandSeparator'));
    }

    public function price_format($price){
        if($price == 0){
            return trans('site::price.help.price');
        }
        return trim(config('cart.symbol_left') . ' '. number_format($price, config('cart.decimals'), config('cart.decimalPoint'), config('cart.thousandSeparator')) .' '. config('cart.symbol_right'));
    }

    /**
     * @return string
     */
    function weight_format()
    {
        return number_format($this->weight(), config('cart.weight_decimals'), config('cart.weight_decimalPoint'), config('cart.weight_thousandSeparator'));
    }

    /**
     * @return float|int
     */
    function weight()
    {
        $weight = 0.00;

        foreach ($this->items as $item) {
            if ($item instanceof Buyable) {

                $weight += $item->weight();
            }
        }

        $weight = $this->weight_convert($weight);

        return $weight;
    }

    public function weight_convert($weight)
    {
        if (config('cart.weight_conversion') === true) {
            if (config('cart.weight_input') != config('cart.weight_output')) {
                $units = config('cart.weight_units');
                $weight = $weight * $units[config('cart.weight_input')] / $units[config('cart.weight_output')];
            }
        }

        return $weight;
    }

}