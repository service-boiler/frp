<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\ProductSpec\SearchFilter;
use ServiceBoiler\Prf\Site\Filters\ProductSpec\SortByNameFilter;
use ServiceBoiler\Prf\Site\Http\Resources\ProductSpecCollection;
use ServiceBoiler\Prf\Site\Models\ProductSpec;
use ServiceBoiler\Prf\Site\Repositories\ProductSpecRepository;

class ProductSpecController extends Controller
{
    protected $productSpecs;

    /**
     * Create a new controller instance.
     *
     * @param ProductSpecRepository $productSpecs
     */
    public function __construct(ProductSpecRepository $productSpecs)
    {
        $this->productSpecs = $productSpecs;
    }

    /**
     * @return ProductSpecCollection
     */
    public function index()
    {   
        
        $this->productSpecs->applyFilter(new SearchFilter());
        $this->productSpecs->applyFilter(new SortByNameFilter());
        
        return new ProductSpecCollection($this->productSpecs->all());
    }

    /**
     * @param ProductSpec $academyVideo
     * @return \Illuminate\View\View
     */
    public function create(ProductSpec $spec)
    {
        return view('site::admin.product.spec.create', compact('spec'));
    } 
   
}