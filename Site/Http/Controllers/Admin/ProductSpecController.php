<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\ProductSpec\SortByNameFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\ProductSpecRequest;
use ServiceBoiler\Prf\Site\Models\ProductSpec;
use ServiceBoiler\Prf\Site\Repositories\ProductSpecRepository;


class ProductSpecController extends Controller
{

    use AuthorizesRequests;

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
     * Show the user profile
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->productSpecs->applyFilter(new SortByNameFilter());
        
        return view('site::admin.product_spec.index', [
            'repository' => $this->productSpecs,
            'productspecs' => $this->productSpecs->paginate(100, ['product_specs.*'])
        ]);
    }

    
	/**
	 * @param FileRequest $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function create(ProductSpecRequest $request)
    {
        return view('site::admin.product_spec.create');
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ProductSpecRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductSpecRequest $request)
    {
          
        $spec = $this->productSpecs->create($request->input('productspec'));
        
         return redirect()->route('admin.productspecs.index')->with('success', trans('site::product.spec_created'));
    }

    public function edit(ProductSpec $productspec)
    {
        $specs = ProductSpec::whereHas('products', function ($query) use($productspec) {
                                            $query->whereIn( 'products.id', $productspec->products->pluck('id'));
                                        })->orderBy('sort_order')->get();
        
        return view('site::admin.product_spec.edit', compact('productspec','specs'));
    }

    public function update(ProductSpecRequest $request, ProductSpec $productspec)
    {   
                
        $upd=$productspec->update($request->input('productspec'));
        
        return redirect()->route('admin.productspecs.index')->with('success', trans('site::product.spec_updated'));
    }
    
    public function destroy(ProductSpec $spec)
    {

        if ($spec->delete()) {
            return redirect()->route('academy-admin.programs.index')->with('success', trans('site::academy-admin.program_deleted'));
        } else {
            return redirect()->route('academy-admin.programs.show', $spec)->with('error', trans('site::academy-admin.program_error_deleted'));
        }
    }


}