<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use ServiceBoiler\Prf\Site\Filters\EnabledFilter;
use ServiceBoiler\Prf\Site\Models\Datasheet;
use ServiceBoiler\Prf\Site\Models\Equipment;
use ServiceBoiler\Prf\Site\Models\ProductSpec;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Repositories\EquipmentRepository;
use ServiceBoiler\Prf\Site\Http\Resources\EquipmentSpecificationsCollection;

class EquipmentController extends Controller
{
    protected $equipments;

    /**
     * Create a new controller instance.
     *
     * @param EquipmentRepository $equipments
     */
    public function __construct(EquipmentRepository $equipments)
    {
        $this->equipments = $equipments;
    }

    /**
     * Show the equipment index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->equipments->trackFilter();
        $this->equipments->applyFilter(new EnabledFilter());

        return view('site::equipment.index', [
            'equipments' => $this->equipments->all(['equipments.*'])
        ]);
    }

    public function show(Equipment $equipment)
    {   
        $specs=ProductSpec::whereHas('products', function ($query) use($equipment) {
                                            $query->where('equipment_id', $equipment->id);
                                        })->orderBy('sort_order')->get();
    
    
        if (
            $equipment->getAttribute(config('site.check_field')) === false
            || $equipment->getAttribute('enabled') === false
        ) {
            abort(404);
        }
        if(!empty(DB::table('variables')->where('name','equipment_description_addon')->first())){
        $equipment_description_addon = DB::table('variables')->where('name','equipment_description_addon')->first()->value;
        }
        $products = $equipment->products()
            ->where('enabled', 1)
	        ->where(config('site.check_field'), 1)
            ->orderBy('name')
            ->get();
        $reviews = $equipment->reviews()
            ->where('status_id', 2)
	        ->orderBy('created_at')
            ->get();
        $datasheets = Datasheet::query()
            ->where('active', 1)
            ->whereRaw("(datasheets.type_id != 25 OR datasheets.type_id IS NULL)")
            ->whereHas('products', function ($query) use ($equipment) {
                $query->where('enabled', 1)->where('equipment_id', $equipment->id);
            })->get();
        $modelises = Datasheet::query()
            ->where('active', 1)
            ->where('type_id', 25)
            ->whereHas('products', function ($query) use ($equipment) {
                $query->where('enabled', 1)->where('equipment_id', $equipment->id);
            })->get();

        return view('site::equipment.show', compact('equipment', 'products', 'datasheets','equipment_description_addon','reviews','modelises','specs'));
    }

    
}