<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use ServiceBoiler\Prf\Site\Filters\Product\EquipmentFilter;
use ServiceBoiler\Prf\Site\Filters\Product\HasEquipmentFilter;
use ServiceBoiler\Prf\Site\Filters\Product\MountingFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductEquipmentFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductMounterFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Product\SearchFilter;
use ServiceBoiler\Prf\Site\Filters\ProductCanBuyFilter;
use ServiceBoiler\Prf\Site\Filters\Product\LimitFilter;
use ServiceBoiler\Prf\Site\Http\Resources\ProductCollection;
use ServiceBoiler\Prf\Site\Http\Resources\ProductShortCollection;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Models\Serial;
use ServiceBoiler\Prf\Site\Repositories\ProductRepository;

class ProductController extends Controller
{
    protected $products;

    /**
     * Create a new controller instance.
     *
     * @param ProductRepository $products
     */
    public function __construct(ProductRepository $products)
    {
        $this->products = $products;
    }

    public function mounting()
    {
        $this->products->applyFilter(new MountingFilter());
        $this->products->applyFilter(new SearchFilter());

        return new ProductCollection($this->products->all());
    }

    public function mounter()
    {
        $this->products->applyFilter(new ProductMounterFilter());
        return new ProductCollection($this->products->all());
    }

    /**
     * @return ProductCollection
     */
    public function repair()
    {
        $this->products->applyFilter(new SearchFilter());
        $this->products->applyFilter(new ProductSearchFilter());

        return new ProductCollection($this->products->all());
    }

    /**
     * @return ProductCollection
     */
    public function fast()
    {

        $this->products->applyFilter(new ProductCanBuyFilter());
        $this->products->applyFilter(new SearchFilter());
        $this->products->applyFilter(new LimitFilter());

        return new ProductCollection($this->products->all());
    }

    public function eqSearch()
    {

        $this->products->applyFilter(new HasEquipmentFilter());
        $this->products->applyFilter(new SearchFilter());
        $this->products->applyFilter(new LimitFilter());

        return new ProductShortCollection($this->products->all());
    }

    public function search()
    {

        $this->products->applyFilter(new ProductEquipmentFilter());
        $this->products->applyFilter(new SearchFilter());
        $this->products->applyFilter(new LimitFilter());

        return new ProductShortCollection($this->products->all());
    }

    /**
     * @param Product $product
     * @return \Illuminate\View\View
     */
    public function part(Product $product)
    {
        return view('site::part.card', collect([
            'product_id' => $product->id,
            'sku'        => $product->sku,
            'image'      => $product->image()->src(),
            'cost'       => $product->hasPrice ? $product->repairPrice->value : 0,
            'format'     => $product->hasPrice ? Site::format($product->repairPrice->value): trans('site::price.error.price'),
            'name'       => $product->name,
            'count'      => 1,
        ]));
    }
    
    public function serialProduct(Request $request)
    {
        $serial=preg_replace(config('site.serial_pattern.pattern'), config('site.serial_patternt.replacement'), $request->serial);
        $serial_id = Serial::find($serial);
        
        if(!empty($serial_id)) {
            $product=$serial_id->product;
            return(['product_id'=>$product->id, 'product_name'=>$product->name, 'equipment_id'=>$product->equipment->id]);
        } else {
            return ['product_id'=>'empty'];
        }
        
    }
}