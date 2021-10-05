<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use ServiceBoiler\Prf\Site\Filters\ProductRetailSaleBonus\ProductRetailSaleBonusPerPageFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\ProductRetailSaleBonusRequest;
use ServiceBoiler\Prf\Site\Models\ProductRetailSaleBonus;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Repositories\ProductRetailSaleBonusRepository;

class ProductRetailSaleBonusController extends Controller
{
   
    protected $product_retail_sale_bonuses;

    public function __construct(ProductRetailSaleBonusRepository $product_retail_sale_bonuses)
    {      
        $this->product_retail_sale_bonuses = $product_retail_sale_bonuses;
    }

    public function index(ProductRetailSaleBonusRequest $request)
    {
        $this->product_retail_sale_bonuses->trackFilter();
        $this->product_retail_sale_bonuses->pushTrackFilter(ProductRetailSaleBonusPerPageFilter::class);

        return view('site::admin.product_retail_sale_bonus.index', [
            'repository'       => $this->product_retail_sale_bonuses,
            'retail_sale_bonuses' => $this->product_retail_sale_bonuses->paginate($request->input('filter.per_page', config('site.per_page.retail_sale_bonus', 100)), ['product_retail_sale_bonuses.*'])
        ]);
    }

    public function create(ProductRetailSaleBonusRequest $request)
    {
        $products = Product::query()
            ->retailSaled()
            ->orderBy('name')
            ->whereDoesntHave('product_retail_sale_bonus')
            ->get();
        $selected_product = Product::query()->findOrNew($request->input('product_id'));
        return view('site::admin.product_retail_sale_bonus.create', compact(
            'products',
            'selected_product'
        ));
    }

    public function store(ProductRetailSaleBonusRequest $request)
    {
        $product_retail_sale_bonus = $this->product_retail_sale_bonuses->create($request->input(['retail_sale_bonus']));

        return redirect()->route('admin.products.show', $product_retail_sale_bonus->product)->with('success', trans('site::product.retail_sale_bonus.created'));
    }

    public function edit(ProductRetailSaleBonus $retail_sale_bonus)
    {
        return view('site::admin.product_retail_sale_bonus.edit', compact('retail_sale_bonus'));
    }

    public function update(ProductRetailSaleBonusRequest $request, ProductRetailSaleBonus $retail_sale_bonus)
    {
      
        $retail_sale_bonus->update($request->input(['retail_sale_bonus']));

        return redirect()->route('admin.products.show', $retail_sale_bonus->product)->with('success', trans('site::product.retail_sale_bonus.updated'));
    }

    
    public function destroy(ProductRetailSaleBonus $retail_sale_bonus)
    {
        if ($retail_sale_bonus->delete()) {
            Session::flash('success', trans('site::product.retail_sale_bonus.deleted'));
        } else {
            Session::flash('error', trans('site::product.retail_sale_bonus.error_deleted'));
        }
        $json['redirect'] = route('admin.products.show', $retail_sale_bonus->product);

        return response()->json($json);
    }

}