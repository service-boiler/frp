<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use ServiceBoiler\Prf\Site\Concerns\StoreImages;
use ServiceBoiler\Prf\Site\Http\Requests\ImageRequest;
use ServiceBoiler\Prf\Site\Models\Equipment;


class EquipmentImageController extends Controller
{

    use StoreImages;

    public function index(ImageRequest $request, Equipment $equipment)
    {
        $images = $this->getImages($request, $equipment);
        return view('site::admin.equipment.image.index', compact('equipment', 'images'));
    }

	/**
	 * @param \ServiceBoiler\Prf\Site\Http\Requests\ImageRequest $request
	 * @param \ServiceBoiler\Prf\Site\Models\Equipment $equipment
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
    public function store(ImageRequest $request, Equipment $equipment)
    {
        return $this->storeImages($request, $equipment);
    }

}