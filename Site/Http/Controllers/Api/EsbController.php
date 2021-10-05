<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use ServiceBoiler\Prf\Site\Filters\EsbUserProduct\EsbUserProductSerialSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductMounterFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Product\SearchFilter;
use ServiceBoiler\Prf\Site\Filters\ProductCanBuyFilter;
use ServiceBoiler\Prf\Site\Filters\Product\LimitFilter;
use ServiceBoiler\Prf\Site\Http\Resources\EsbUserProductCollection;
use ServiceBoiler\Prf\Site\Http\Resources\EsbUserProductSmCollection;
use ServiceBoiler\Prf\Site\Models\EsbContractField;
use ServiceBoiler\Prf\Site\Models\EsbContractTemplate;
use ServiceBoiler\Prf\Site\Models\EsbUserProduct;
use ServiceBoiler\Prf\Site\Models\Serial;
use ServiceBoiler\Prf\Site\Repositories\ProductRepository;
use ServiceBoiler\Prf\Site\Repositories\EsbUserProductRepository;

class EsbController extends Controller
{
    protected $products;

    /**
     * Create a new controller instance.
     *
     * @param ProductRepository $products
     */
    public function __construct(ProductRepository $products, EsbUserProductRepository $esbUserProducts)
    {
        $this->products = $products;
        $this->esbUserProducts = $esbUserProducts;
    }

    public function serialSearch($serial=null)
    {
      if($serial && auth()->user()){
            $esbProducts=EsbUserProduct::where('serial',$serial)->get();
            
            if( $esbProducts->count() && ($esbProducts->first()->esbUser->esbRequests()->where('status_id','!=',7)->where('recipient_id',auth()->user()->id)->exists()
                 || $esbProducts->first()->esbUser->esbServices()->wherePivot('enabled',1)->get()->contains(auth()->user()->id) )) {
                    return new EsbUserProductCollection($esbProducts);
                    
            } else {
                return new EsbUserProductSmCollection($esbProducts);
            }
            
       } else return [];
    }
    public function phoneSearch($phone=null)
    {
        $phone=preg_replace('/\D/', '', $phone);
        if(substr($phone,0,1)=='8' && strlen($phone)>10) { $phone=substr($phone,1,10);}
        
        if($phone && auth()->user()){
            $esbProducts=EsbUserProduct::where('enabled',1)->whereHas('esbUser', function ($q) use ($phone){$q->where('phone',$phone);})->get();
            if( $esbProducts->count() && ($esbProducts->first()->getPermissionServiceAttribute() )) {
                return new EsbUserProductCollection($esbProducts);
                        
            } else {
                return new EsbUserProductSmCollection($esbProducts);
            }
       } else return [];
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
    public function esbContractTemplateAddField(Request $request)
    {
        if(EsbContractField::query()->where('name',$request->add_field_name)->where('preset',1)->exists()){
            return ['exists'=>1];
        } else {
            return view('site::esb_contract_template.add_field',['name'=>$request->add_field_name,'title'=>$request->add_field_title]);
        }


    }
}