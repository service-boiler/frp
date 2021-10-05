<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use ServiceBoiler\Prf\Site\Facades\Site;
use ServiceBoiler\Prf\Site\Filters\Product\ProductSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Product\SearchFilter;
use ServiceBoiler\Prf\Site\Http\Resources\ProductCollection;
use ServiceBoiler\Prf\Site\Models\Currency;
use ServiceBoiler\Prf\Site\Models\Equipment;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Repositories\ProductRepository;

class PartController extends Controller
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
        $this->products->applyFilter(new SearchFilter());
        $this->products->applyFilter(new ProductSearchFilter());

        return new ProductCollection($this->products->all());
    }
    
    public function search()
    {
        $this->products->applyFilter(new SearchFilter());
       
        return new ProductCollection($this->products->all());
    }

    /**
     * @param Product $product
     * @return \Illuminate\View\View
     */
    public function create(Product $product)
    {   $repair_price_ratio = !empty(Auth::user()->repair_price_ratio) ? Auth::user()->repair_price_ratio : 1;
        return view('site::part.create', compact('product','repair_price_ratio'));
    }
    public function createTender(Product $product)
    {  
        if($product->retailPriceEur->value!=0){
            $euroRate = Site::currencyRates(Currency::find('643'), $product->retailPriceEur->currency, Carbon::now());
            return view('site::part.create_tender', compact('product'));
       
        } else {
        return view('site::part.no_price', compact('product'));
        }
         
    }
    public function createShort (Product $product)
    {  
        
        return view('site::part.product_short', compact('product'));
         
    }
    
    public function createRevisionPart (Product $product)
    {  
        
        return view('site::part.create_revision_part', compact('product'));
         
    }
    
    public function createRevisionPartFromEquipment (Equipment $equipment)
    {  
        
        return view('site::part.create_revision_part_from_equipment', compact('equipment'));
         
    }
}