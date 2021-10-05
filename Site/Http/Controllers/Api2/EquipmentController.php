<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api2;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use ServiceBoiler\Prf\Site\Filters\EnabledFilter;

use ServiceBoiler\Prf\Site\Http\Resources\EquipmentCollection;

use ServiceBoiler\Prf\Site\Models\Equipment;
use ServiceBoiler\Prf\Site\Repositories\EquipmentRepository;

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

    public function show(Equipment $equipment)
    {
        return new EquipmentCollection(Equipment::find($equipment));
    }

   public function index()
    {
        $this->equipments->applyFilter(new EnabledFilter());

        return new EquipmentCollection($this->equipments->all());
    }

    

}