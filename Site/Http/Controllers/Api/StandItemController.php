<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\Product\ProductForStandFilter;
use ServiceBoiler\Prf\Site\Filters\Product\SearchFilter;
use ServiceBoiler\Prf\Site\Http\Resources\ProductCollection;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Repositories\ProductRepository;

class StandItemController extends Controller
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

    /**
     * @return ProductCollection
     */
    public function index()
    {   
        
        $this->products->applyFilter(new ProductForStandFilter());
        $this->products->applyFilter(new SearchFilter());
        
        
        return new ProductCollection($this->products->all());
    }

    /**
     * @param Product $product
     * @return \Illuminate\View\View
     */
    public function create(Product $product)
    {
        return view('site::stand_item.create', compact('product'));
    } 
    public function createAdmin(Product $product)
    {   
        return view('site::stand_item.create_admin', compact('product'));
    }
}