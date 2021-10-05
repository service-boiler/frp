<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use ServiceBoiler\Prf\Site\Concerns\StoreImages;
use ServiceBoiler\Prf\Site\Events\AddressUpdateEvent;
use ServiceBoiler\Prf\Site\Http\Requests\ImageRequest;
use ServiceBoiler\Prf\Site\Models\Address;
use ServiceBoiler\Prf\Site\Models\Image;


class AddressImageController extends Controller
{

    use StoreImages;

    public function index(ImageRequest $request, Address $address)
    {
        $images = $this->getImages($request, $address);
        return view('site::admin.address.image.index', compact('address', 'images'));
    }

	/**
	 * @param \ServiceBoiler\Prf\Site\Http\Requests\ImageRequest $request
	 * @param \ServiceBoiler\Prf\Site\Models\Address $address
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
    public function store(ImageRequest $request, Address $address)
    {		
		return $this->storeImages($request, $address, 'site::address.image.edit');
    }
	 
    public function show(Address $address, Image $image)
    {
        return Storage::disk($image->getAttribute('storage'))->download($image->getAttribute('path'), $image->getAttribute('name'));
    }
	 
	 public function destroy(Address $address, Image $image)
    {
        $json = [];
        if ($image->delete()) {
            $json['remove'][] = '#image-' . $image->getAttribute('id');
        }

        return response()->json($json);

    }

}