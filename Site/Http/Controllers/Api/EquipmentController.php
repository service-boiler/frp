<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


use ServiceBoiler\Prf\Site\Filters\Equipment\EquipmentBrandFilter;
use ServiceBoiler\Prf\Site\Filters\Equipment\SortByNameFilter;
use ServiceBoiler\Prf\Site\Http\Resources\EquipmentCollection;
use ServiceBoiler\Prf\Site\Filters\Equipment\SearchFilter;
use ServiceBoiler\Prf\Site\Models\Equipment;
use ServiceBoiler\Prf\Site\Models\ProductSpec;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Repositories\EquipmentRepository;

class EquipmentController extends Controller
{
    protected $equipments;

    public function __construct(EquipmentRepository $equipments)
    {
        $this->equipments = $equipments;
    }

       
    public function search()
    {
        $this->equipments->applyFilter(new SearchFilter());
        $this->equipments->applyFilter(new EquipmentBrandFilter());
        $this->equipments->applyFilter(new SortByNameFilter());

        return new EquipmentCollection($this->equipments->all());
    } 
       
    public function show(Equipment $equipment)
    {
        
        return new EquipmentCollection($equipment);
    
    }   
    public function specifications(Request $request,Equipment $equipment)
    {   
        $specs=ProductSpec::whereHas('products', function ($query) use($equipment) {
                                            $query->where('equipment_id', $equipment->id);
                                        })->orderBy('sort_order')->get();
        $products = $equipment->availableProducts()->get();
        
        if($products->count() % 7 == 0) {$cols=7;}
        elseif($products->count() % 5 == 0) {$cols=5;}
        elseif($products->count() % 6 == 0) {$cols=6;}
        elseif($products->count() == 8) {$cols=4;}
        elseif($products->count() == 9) {$cols=5;}
        elseif($products->count() == 11) {$cols=6;}
        elseif($products->count() == 16) {$cols=6;}
        elseif($products->count() == 17) {$cols=6;}
        else {$cols=7;}
        
        $screenwidth = $request->screenwidth;
        
        return view('site::equipment.specifications', compact('equipment','screenwidth','specs','cols'));
    
    }
    
    
}