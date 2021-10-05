<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\EsbCatalogService\EsbCatalogServiceTypeSelectFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\EsbCatalogServiceRequest;
use ServiceBoiler\Prf\Site\Models\Brand;
use ServiceBoiler\Prf\Site\Models\EsbCatalogService;
use ServiceBoiler\Prf\Site\Repositories\EsbCatalogServiceRepository;
use ServiceBoiler\Prf\Site\Repositories\EsbCatalogServiceTypeRepository;

class EsbCatalogServiceController extends Controller
{

    protected $esbCatalogServices;

    /**
     * Create a new controller instance.
     *
     * @param EsbCatalogServiceRepository $esbCatalogServices
     */
    public function __construct(EsbCatalogServiceRepository $esbCatalogServices, EsbCatalogServiceTypeRepository $esbCatalogServiceTypes)
    {
        $this->esbCatalogServices = $esbCatalogServices;
        $this->esbCatalogServiceTypes = $esbCatalogServiceTypes;
    }

    /**
     * Show the shop index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->esbCatalogServices->trackFilter();
        $this->esbCatalogServices->pushTrackFilter(EsbCatalogServiceTypeSelectFilter::class);

        return view('site::admin.esb_catalog_service.index', [
            'repository' => $this->esbCatalogServices,
            'esbCatalogServices'  => $this->esbCatalogServices->paginate(config('site.per_page.esb_catalog_service', 10), ['esb_catalog_services.*'])
        ]);
    }

    public function create()
    {
        $sort_order = $this->esbCatalogServices->count();
        $esbCatalogServiceTypes = $this->esbCatalogServiceTypes->all();
        $brands = Brand::where('enabled',1)->get();

        return view('site::admin.esb_catalog_service.create', compact('sort_order','esbCatalogServiceTypes','brands'));
    }


    /**
     * @param EsbCatalogService $esbCatalogService
     * @return \Illuminate\Http\Response
     */
    public function edit(EsbCatalogService $esbCatalogService)
    {
        $esbCatalogServiceTypes = $this->esbCatalogServiceTypes->all();
        $brands = Brand::where('enabled',1)->get();

        return view('site::admin.esb_catalog_service.edit', compact('esbCatalogService','esbCatalogServiceTypes','brands'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  EsbCatalogServiceRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(EsbCatalogServiceRequest $request)
    {
        $esbCatalogService = $this->esbCatalogServices->create([
            'name'=>$request->input('name'),
            'type_id'=>$request->input('type_id'),
            'brand_id'=>$request->input('brand_id'),
            'enabled'=>$request->input('enabled'),
            ]);
        $esbCatalogService->shared=$request->input('shared');
        $esbCatalogService->created_by=auth()->user()->id;
        $esbCatalogService->save();
        if($request->input('cost_std')) {
            $price = $esbCatalogService->prices()->create(['price' => $request->input('cost_std'), 'service_id' => $esbCatalogService->id]);
            $price->company_id = $esbCatalogService->shared ? 1 : auth()->user()->company()->id;
            $price->save();
        }


        return redirect()->route('admin.esb-catalog-services.index')->with('success', trans('site::admin.esb_catalog_service.created'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  EsbCatalogServiceRequest $request
     * @param  EsbCatalogService $esbCatalogService
     * @return \Illuminate\Http\Response
     */
    public function update(EsbCatalogServiceRequest $request, EsbCatalogService $esbCatalogService)
    {


        $esbCatalogService->update([
            'name'=>$request->input('name'),
            'type_id'=>$request->input('type_id'),
            'brand_id'=>$request->input('brand_id'),
            'enabled'=>$request->input('enabled'),
        ]);
        $esbCatalogService->shared=$request->input('shared');
        $esbCatalogService->save();

        if($request->input('cost_std')) {
            $price = $esbCatalogService->prices()->updateOrCreate(['service_id' => $esbCatalogService->id, 'company_id'=>$esbCatalogService->shared ? '1' : auth()->user()->company()->id],['price' => $request->input('cost_std')]);
            $price->company_id = $esbCatalogService->shared ? '1' : auth()->user()->company()->id;
            $price->save();
        }

        return redirect()->route('admin.esb-catalog-services.index')->with('success', trans('site::admin.esb_catalog_service.updated'));
    }

    public function sort(Request $request)
    {
        $sort = array_flip($request->input('sort'));

        foreach ($sort as $esbCatalogService_id => $sort_order) {
            /** @var EsbCatalogService $esbCatalogService */
            $esbCatalogService = EsbCatalogService::find($esbCatalogService_id);
            $esbCatalogService->setAttribute('sort_order', $sort_order);
            $esbCatalogService->save();
        }
    }

    public function destroy(Request $request, EsbCatalogService $esbCatalogService)
    {
        $this->authorize('delete', $esbCatalogService);

        if ($esbCatalogService->delete()) {
            $json['remove'][] = '#esbCatalogService-' . $esbCatalogService->id;
        } else {
            $json['error'][] = 'error';
        }

        return response()->json($json);
    }

}