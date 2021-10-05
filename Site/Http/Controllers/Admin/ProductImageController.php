<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Concerns\StoreImages;
use ServiceBoiler\Prf\Site\Http\Requests\ImageRequest;
use ServiceBoiler\Prf\Site\Models\Product;


class ProductImageController extends Controller
{

    use StoreImages;

    public function index(ImageRequest $request, Product $product)
    {
        $images = $this->getImages($request, $product);

        return view('site::admin.product.image.index', compact('product', 'images'));
    }

    /**
     * @param \ServiceBoiler\Prf\Site\Http\Requests\ImageRequest $request
     * @param \ServiceBoiler\Prf\Site\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ImageRequest $request, Product $product)
    {
        return $this->storeImages($request, $product);
    }

}