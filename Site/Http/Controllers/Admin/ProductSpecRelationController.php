<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\ProductSpecRequest;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Models\ProductSpec;
use ServiceBoiler\Prf\Site\Models\ProductSpecRelation;

class ProductSpecRelationController extends Controller
{

    /**
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        if(!empty($product->equipment)) {
            $specs=ProductSpec::whereHas('products', function ($query) use($product) {
                                            $query->where('equipment_id', $product->equipment->id);
                                        })->orderBy('sort_order')->get();
        } else {
        
        $specs=ProductSpec::whereHas('products', function ($query) use($product) {
                                            $query->where('products.id', $product->id);
                                        })->orderBy('sort_order')->get();
        }
       
        return view('site::admin.product.spec.index', compact('product', 'specs'));
    }

    /**
     * @param ProductDetailRequest $request
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function store(ProductSpecRequest $request, Product $product)
    {
        foreach($request->input('specs') as $spec_id=>$spec_value)
        {
            if($spec_value) $spec_relation = ProductSpecRelation::updateOrCreate(['product_spec_id' => $spec_id, 'product_id' => $product->id], ['spec_value' => $spec_value]);
        }
       foreach($product->specs as $product_spec)
       {
            if(!in_array($product_spec->id,array_keys($request->input('specs')))) {
                $spec_relation=ProductSpecRelation::where('product_spec_id',$product_spec->id)->where('product_id',$product->id)->delete();
            }
       }

        return redirect()->route('admin.products.specs.index', $product)->with('success', trans('site::product.updated'));
    }

    public function destroy(ProductDetailRequest $request, Product $product)
    {

        $product->detachDetails($request->input('delete'));

        return redirect()->route('admin.products.details.index', $product)->with('success', trans('site::product.deleted'));
    }
}