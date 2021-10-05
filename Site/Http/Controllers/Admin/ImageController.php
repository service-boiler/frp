<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Http\File;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use ServiceBoiler\Prf\Site\Concerns\StoreImages;
use ServiceBoiler\Prf\Site\Contracts\Imageable;
use ServiceBoiler\Prf\Site\Http\Requests\ImageRequest;
use ServiceBoiler\Prf\Site\Jobs\ProcessImage;
use ServiceBoiler\Prf\Site\Models\Image;

//use ServiceBoiler\Prf\Site\Repositories\ImageRepository;

class ImageController extends Controller
{

    use StoreImages;

//    protected $images;
//
//    /**
//     * Create a new controller instance.
//     *
//     * @param ImageRepository $images
//     */
//    public function __construct(ImageRepository $images)
//    {
//        $this->images = $images;
//    }
//
//    /**
//     * Show the user profile
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function index()
//    {
//
//        $this->images->trackFilter();
//
//        return view('admin.image.index', [
//            'repository' => $this->images,
//            'items'      => $this->images->paginate(config('site.per_page.image', 10), ['images.*'])
//        ]);
//    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  ImageRequest $request
     * @param Imageable $imageable
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ImageRequest $request, Imageable $imageable = null)
    {   
        return $this->storeImages($request, $imageable);

    }

    public function show(Image $image)
    {
        return Storage::disk($image->getAttribute('storage'))->download($image->getAttribute('path'), $image->getAttribute('name'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Image $image
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Image $image)
    {
        $json = [];
        if ($image->delete()) {
            $json['remove'][] = '#image-' . $image->getAttribute('id');
        }

        return response()->json($json);

    }
}