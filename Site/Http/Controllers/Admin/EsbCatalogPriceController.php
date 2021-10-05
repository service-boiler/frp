<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\EnabledFilter;
use ServiceBoiler\Prf\Site\Filters\EsbCatalogPrice\EsbCatalogPriceOwnerFilter;
use ServiceBoiler\Prf\Site\Filters\EsbCatalogPrice\EsbCatalogPriceScSearchFilter;
use ServiceBoiler\Prf\Site\Filters\EsbCatalogPrice\EsbCatalogPriceServiceTypeSelectFilter;
use ServiceBoiler\Prf\Site\Filters\EsbCatalogService\EsbCatalogServiceOwnerFilter;
use ServiceBoiler\Prf\Site\Http\Requests\EsbCatalogServiceRequest;
use ServiceBoiler\Prf\Site\Http\Requests\EsbCatalogPriceRequest;
use ServiceBoiler\Prf\Site\Models\Brand;
use ServiceBoiler\Prf\Site\Models\EsbCatalogService;
use ServiceBoiler\Prf\Site\Models\EsbCatalogServicePrice;
use ServiceBoiler\Prf\Site\Repositories\EsbCatalogServiceRepository;
use ServiceBoiler\Prf\Site\Repositories\EsbCatalogPriceRepository;
use ServiceBoiler\Prf\Site\Repositories\EsbCatalogServiceTypeRepository;

class EsbCatalogPriceController extends Controller
{
    use AuthorizesRequests;

    protected $esbCatalogPrices;

    /**
     * Create a new controller instance.
     *
     * @param EsbCatalogServiceRepository $esbCatalogPrices
     */
    public function __construct(EsbCatalogServiceRepository $esbCatalogServices, EsbCatalogPriceRepository $esbCatalogPrices, EsbCatalogServiceTypeRepository $esbCatalogServiceTypes)
    {
        $this->esbCatalogServices = $esbCatalogServices;
        $this->esbCatalogPrices = $esbCatalogPrices;
        $this->esbCatalogServiceTypes = $esbCatalogServiceTypes;
    }

    /**
     * Show the shop index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->esbCatalogPrices->trackFilter();
        $this->esbCatalogPrices->pushTrackFilter(EsbCatalogPriceScSearchFilter::class);
        $this->esbCatalogPrices->pushTrackFilter(EsbCatalogPriceServiceTypeSelectFilter::class);

        return view('site::admin.esb_catalog_price.index', [
            'repository' => $this->esbCatalogPrices,
            'esbCatalogPrices'  => $this->esbCatalogPrices->paginate(config('site.per_page.esb_catalog_service', 10), ['esb_catalog_service_prices.*'])
        ]);
    }




    /**
     * @param EsbCatalogService $esbCatalogPrice
     * @return \Illuminate\Http\Response
     */
    public function edit(EsbCatalogServicePrice $esbCatalogPrice)
    {
        $this->authorize('edit', $esbCatalogPrice);
        $this->esbCatalogServices->applyFilter(new EnabledFilter());
        $esbCatalogServices = $this->esbCatalogServices->all();

        $brands = Brand::where('enabled',1)->get();

        return view('site::admin.esb_catalog_price.edit', compact('esbCatalogPrice','esbCatalogServices','brands'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  EsbCatalogServiceRequest $request
     * @param  EsbCatalogService $esbCatalogPrice
     * @return \Illuminate\Http\Response
     */
    public function update(EsbCatalogPriceRequest $request, EsbCatalogServicePrice $esbCatalogPrice)
    {
        $this->authorize('edit', $esbCatalogPrice);
        $esbCatalogPrice->update([
            'service_id'=>$request->input('service_id'),
            'price'=>$request->input('price'),
            'enabled'=>$request->input('enabled'),
        ]);

        return redirect()->route('admin.esb-catalog-prices.index')->with('success', trans('site::admin.esb_catalog_service.price_updated'));
    }


    public function destroy(Request $request, EsbCatalogServicePrice $esbCatalogPrice)
    {
        $this->authorize('delete', $esbCatalogPrice);

        if ($esbCatalogPrice->delete()) {
            $json['remove'][] = '#esbCatalogPrice-' . $esbCatalogPrice->id;
        } else {
            $json['error'][] = 'error';
        }

        return response()->json($json);
    }

}