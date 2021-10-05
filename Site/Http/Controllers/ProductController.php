<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use ServiceBoiler\Prf\Site\Exports\Excel\ProductPriceExcel;


use ServiceBoiler\Prf\Site\Filters;
use ServiceBoiler\Prf\Site\Filters\Equipment\HasProductFilter;
use ServiceBoiler\Prf\Site\Filters\Equipment\SortFilter;
use ServiceBoiler\Prf\Site\Filters\Equipment\EquipmentShowFilter;
use ServiceBoiler\Prf\Site\Filters\Product\BoilerFilter;
use ServiceBoiler\Prf\Site\Filters\Product\EquipmentFilter;
use ServiceBoiler\Prf\Site\Filters\Product\HasNameFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductShowFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductSortOrderFilter;
use ServiceBoiler\Prf\Site\Filters\Product\TypeFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductHasOldPriceFilter;
use ServiceBoiler\Prf\Site\Filters\ProductCanBuyFilter;

use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Models\ProductSpec;
use ServiceBoiler\Prf\Site\Models\Scheme;
use ServiceBoiler\Prf\Site\Models\Variable;

use ServiceBoiler\Prf\Site\Repositories\EquipmentRepository;
use ServiceBoiler\Prf\Site\Repositories\ProductRepository;
use ServiceBoiler\Prf\Site\Repositories\HeadBannerBlockRepository;


class ProductController extends Controller
{

    use AuthorizesRequests;
    /**
     * @var ProductRepository
     */
    protected $products;
    /**
     * @var EquipmentRepository
     */
    private $equipments;

    /**
     * Create a new controller instance.
     *
     * @param ProductRepository $products
     * @param EquipmentRepository $equipments
     */
    public function __construct(ProductRepository $products, 
        EquipmentRepository $equipments,
        HeadBannerBlockRepository $headBannerBlocks)
    {
        $this->products = $products;
        $this->equipments = $equipments;
        $this->headBannerBlocks = $headBannerBlocks;
    }

    /**
     * Show the shop index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->products->trackFilter();
        $this->products->applyFilter(new ProductSortOrderFilter());
        $this->products->applyFilter(new ProductCanBuyFilter());
        $this->products->applyFilter(new ProductShowFilter());
        $this->products->applyFilter(new HasNameFilter());
        $this->products->pushTrackFilter(TypeFilter::class);
        $this->products->pushTrackFilter(EquipmentFilter::class);
        $this->products->pushTrackFilter(BoilerFilter::class);
        $this->products->pushTrackFilter(ProductHasOldPriceFilter::class); 
        
        $static_currency = Variable::whereName('currency_static_euro')->first();		
        $headBannerBlocks = $this->headBannerBlocks
            ->applyFilter(new Filters\ShowFilter())
            ->applyFilter(new Filters\BySortOrderFilter())
            ->applyFilter(new Filters\PathUrlFilter())
            ->all(['head_banner_blocks.*']);
			
		
        if ($request->has('excel')) {
			(new ProductPriceExcel())->setRepository($this->products)->render();
		}

        return view('site::product.index', [
            'repository' => $this->products,
            'headBannerBlocks' => $headBannerBlocks,
            'products'   => $this->products->paginate(config('site.per_page.product', 20)),
            'static_currency' => $static_currency,
        ]);
    }

	/**
	 * Show the shop index page
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
    public function list(Request $request)
    {
        $this->authorize('list', Product::class);
        $this->products->trackFilter();
        $this->products->applyFilter(new ProductCanBuyFilter());
        $this->products->applyFilter(new HasNameFilter());
        $this->products->pushTrackFilter(TypeFilter::class);
        $this->products->pushTrackFilter(EquipmentFilter::class);
        $this->products->pushTrackFilter(ProductHasOldPriceFilter::class); 
        
        $static_currency = Variable::whereName('currency_static_euro')->first();	
		
        if ($request->has('excel')) {
			(new ProductPriceExcel())->setRepository($this->products)->render();
		}
        
        return view('site::product.list', [
            'repository' => $this->products,
            'products'   => $this->products->paginate(config('site.per_page.product_list', 50)),
            'static_currency' => $static_currency,
        ]);
    }

    /**
     * Show the product page
     *
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {   
            
        if ($product->getAttribute(config('site.check_field')) === false || $product->getAttribute('enabled') === false) {
            abort(404);
        }
        if(!empty(DB::table('variables')->where('name','product_description_addon')->first())){
        $product_description_addon = DB::table('variables')->where('name','product_description_addon')->first()->value;
        }
        $equipments = $this
            ->equipments
            ->applyFilter(new SortFilter())
            ->applyFilter(new EquipmentShowFilter())
            ->applyFilter((new HasProductFilter())->setProduct($product))
            ->all();
        $analogs = $product->analogs()->where('enabled', 1)->where(config('site.check_field'), 1)->orderBy('name')->get();
        $back_relations = $product->relations()->where('enabled', 1)->where(config('site.check_field'), 1)->orderBy('name')->get();
        $details = $product->details()->where('enabled', 1)->where(config('site.check_field'), 1)->orderBy('name')->get();
        $images = $product->images()->get();
        $schemes = $product->schemes()->get();
        $datasheets = $product->datasheets()->with('schemes')->get();
        $modelises = $product->datasheets()->where('type_id','25')->get();
        
        $reviews = $product->reviews()
            ->where('status_id', 2)
	        ->orderBy('created_at')
            ->get();

        $storehouse_addresses = $product->storehouseAddresses();
        $storehouse_addresses_all = $product->storehouseAllAddresses();

        return view('site::product.show', compact(
            'product',
            'equipments',
            'back_relations',
            'analogs',
            //'relations',
            'storehouse_addresses','storehouse_addresses_all',
            'details',
            'images',
            'schemes',
            'datasheets',
            'product_description_addon','reviews'
        ));
    }

    public function scheme(Product $product, Scheme $scheme)
    {
        $datasheets = $product->datasheets()
            ->has('schemes')
            ->with('schemes')
            ->get();
        $url = ['name' => $product->getAttribute('name')];
        if ($product->canBuy) {
            $url['url'] = route('products.show', $product);
        }
        $elements = $scheme->elements()
            ->with('product')
            ->with('pointers')
            ->with('shapes')
            ->orderBy('sort_order')
            ->get();

        return view('site::product.scheme', compact(
            'product',
            'datasheets',
            'scheme',
            'elements',
            'url'
        ));
    }

}