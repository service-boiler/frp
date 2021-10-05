<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\EsbCatalogService\EsbCatalogServiceTypeSelectFilter;
use ServiceBoiler\Prf\Site\Http\Requests\EsbCatalogServiceRequest;
use ServiceBoiler\Prf\Site\Models\Address;
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

        return view('site::esb_catalog_service.index', [
            'repository' => $this->esbCatalogServices,
            'esbCatalogServices'  => $this->esbCatalogServices->paginate(config('site.per_page.esb_catalog_service', 10), ['esb_catalog_services.*'])
        ]);
    }

    public function create()
    {
        $esbCatalogServiceTypes = $this->esbCatalogServiceTypes->all();
        $brands = Brand::where('enabled',1)->get();

        return view('site::esb_catalog_service.create', compact('esbCatalogServiceTypes','brands'));
    }


    /**
     * @param EsbCatalogService $esbCatalogService
     * @return \Illuminate\Http\Response
     */
    public function edit(EsbCatalogService $esbCatalogService)
    {
        $esbCatalogServiceTypes = $this->esbCatalogServiceTypes->all();
        $brands = Brand::where('enabled',1)->get();

        return view('site::esb_catalog_service.edit', compact('esbCatalogService','esbCatalogServiceTypes','brands'));
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
        $esbCatalogService->shared=0;
        $esbCatalogService->created_by=auth()->user()->id;
        $esbCatalogService->company_id=auth()->user()->id;
        $esbCatalogService->save();

        
        return redirect()->route('esb-catalog-services.index')->with('success', trans('site::admin.esb_catalog_service.created'));
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
        $esbCatalogService->shared=0;
        $esbCatalogService->company_id=auth()->user()->company()->id;
        $esbCatalogService->save();

        return redirect()->route('esb-catalog-services.index')->with('success', trans('site::admin.esb_catalog_service.updated'));
    }

    public function updateAddress(Request  $request, Address $address)
    {


        $address->update([
            'asc_info'=>$request->input('address_asc_info'),
        ]);


        return redirect()->route('esb-catalog-services.index')->with('success', trans('site::admin.esb_catalog_service.updated'));
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