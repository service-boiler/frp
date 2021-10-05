<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\Equipment\EquipmentEnabledBoolFilter;
use ServiceBoiler\Prf\Site\Filters\Equipment\EquipmentPerPageFilter;
use ServiceBoiler\Prf\Site\Filters\Equipment\EquipmentShowFerroliBoolFilter;
use ServiceBoiler\Prf\Site\Filters\Equipment\EquipmentShowLamborghiniBoolFilter;
use ServiceBoiler\Prf\Site\Filters\Equipment\EquipmentShowMarketRuBoolFilter;
use ServiceBoiler\Prf\Site\Filters\Equipment\SortByMenuSortFilter;
use ServiceBoiler\Prf\Site\Filters\ByIdDescSortFilter;
use ServiceBoiler\Prf\Site\Http\Requests\EquipmentRequest;
use ServiceBoiler\Prf\Site\Models;
use ServiceBoiler\Prf\Site\Models\Equipment;
use ServiceBoiler\Prf\Site\Repositories;

class EquipmentController extends Controller
{
    /**
     * @var Repositories\EquipmentRepository
     */
    protected $equipments;
    /**
     * @var Repositories\CatalogRepository
     */
    private $catalogs;
    /**
     * @var Repositories\ImageRepository
     */
    private $images;
    /**
     * @var Repositories\CurrencyRepository
     */
    private $currencies;

    /**
     * Create a new controller instance.
     *
     * @param Repositories\EquipmentRepository $equipments
     * @param Repositories\CatalogRepository $catalogs
     * @param Repositories\ImageRepository $images
     * @param Repositories\CurrencyRepository $currencies
     */
    public function __construct(
        Repositories\EquipmentRepository $equipments,
        Repositories\CatalogRepository $catalogs,
        Repositories\ImageRepository $images,
        Repositories\CurrencyRepository $currencies
    )
    {
        $this->equipments = $equipments;
        $this->catalogs = $catalogs;
        $this->images = $images;
        $this->currencies = $currencies;
    }

    /**
     * Show the user profile
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $this->equipments
            ->trackFilter()
            ->applyFilter(new ByIdDescSortFilter())
            ->pushTrackFilter(EquipmentEnabledBoolFilter::class)
            ->pushTrackFilter(EquipmentShowFerroliBoolFilter::class)
            ->pushTrackFilter(EquipmentShowLamborghiniBoolFilter::class)
            ->pushTrackFilter(EquipmentPerPageFilter::class);

        return view('site::admin.equipment.index', [
            'repository' => $this->equipments,
            'equipments' => $this->equipments->paginate($request->input('filter.per_page', config('site.per_page.equipment', 100)), ['equipments.*'])
        ]);
    }
	 
	 

    public function show(Models\Equipment $equipment)
    {

        return view('site::admin.equipment.show', compact('equipment'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Models\Catalog|null $catalog
     * @return \Illuminate\Http\Response
     */
    public function create(Models\Catalog $catalog = null)
    {

        $currencies = $this->currencies->all();
        $parent_catalog_id = !is_null($catalog) ? $catalog->id : null;
        $tree = $this->catalogs->tree();

        return view('site::admin.equipment.create', compact('currencies', 'parent_catalog_id', 'tree'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  EquipmentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(EquipmentRequest $request)
    {
        $equipment = $this->equipments->create(array_merge(
            $request->input('equipment'),
            [
                'enabled'          => $request->filled('equipment.enabled'),
                'mounter_enabled' => $request->filled('equipment.mounter_enabled'),
                'show_ferroli'     => $request->filled('equipment.show_ferroli'),
                'show_lamborghini' => $request->filled('equipment.show_lamborghini'),
                'show_market_ru' => $request->filled('equipment.show_market_ru')
            ]
        ));

        return redirect()->route('admin.equipments.show', $equipment)->with('success', trans('equipment::catalog.created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Models\Equipment $equipment
     * @return \Illuminate\Http\Response
     */
    public function edit(Models\Equipment $equipment)
    {

        $currencies = $this->currencies->all();
        $tree = $this->catalogs->tree();

        return view('site::admin.equipment.edit', compact('currencies', 'tree', 'equipment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EquipmentRequest $request
     * @param  Models\Equipment $equipment
     * @return \Illuminate\Http\Response
     */
    public function update(EquipmentRequest $request, Models\Equipment $equipment)
    {
        $equipment->update(array_merge(
            $request->input('equipment'),
            [
                'enabled'          => $request->filled('equipment.enabled'),
                'mounter_enabled' => $request->filled('equipment.mounter_enabled'),
                'show_ferroli'     => $request->filled('equipment.show_ferroli'),
                'show_lamborghini' => $request->filled('equipment.show_lamborghini'),
                'show_market_ru' => $request->filled('equipment.show_market_ru')
            ]
        ));

        return redirect()->route('admin.equipments.show', $equipment)->with('success', trans('site::equipment.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Equipment $equipment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Equipment $equipment)
    {

        if ($this->equipments->delete($equipment->id) > 0) {
            $redirect = route('admin.equipments.index');
        } else {
            $redirect = route('admin.equipments.show', $equipment);
        }
        $json['redirect'] = $redirect;

        return response()->json($json);

    }
	 
	 
	 public function equipmentsMarketSort(Request $request)
    {

        $this->equipments
            ->trackFilter()
            ->applyFilter(new SortByMenuSortFilter())
            ->pushTrackFilter(EquipmentEnabledBoolFilter::class)
            ->pushTrackFilter(EquipmentShowFerroliBoolFilter::class)
            ->pushTrackFilter(EquipmentShowLamborghiniBoolFilter::class)
            ->pushTrackFilter(EquipmentShowMarketRuBoolFilter::class)
            ->pushTrackFilter(EquipmentPerPageFilter::class);

        return view('site::admin.equipment.market_sort', [
            'repository' => $this->equipments,
            'equipments' => $this->equipments->paginate($request->input('filter.per_page', config('site.per_page.equipment', 500)), ['equipments.*'])
        ]);
    }

}