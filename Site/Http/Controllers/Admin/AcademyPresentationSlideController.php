<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use ServiceBoiler\Prf\Site\Filters;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Concerns\StoreImages;
use ServiceBoiler\Prf\Site\Concerns\StoreFiles;
use ServiceBoiler\Prf\Site\Filters\BySortOrderFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\AcademyPresentationSlideRequest;
use ServiceBoiler\Prf\Site\Models\AcademyPresentationSlide;
use ServiceBoiler\Prf\Site\Models\FileType;
use ServiceBoiler\Prf\Site\Repositories\AcademyPresentationSlideRepository;
use ServiceBoiler\Prf\Site\Repositories\ImageRepository;

class AcademyPresentationSlideController extends Controller
{
	 use AuthorizesRequests, StoreImages, StoreFiles;
	 
    protected $academyPresentationSlides;
    protected $images;

    /**
     * Create a new controller instance.
     *
     * @param AcademyPresentationSlideRepository $academyPresentationSlides
     */
    public function __construct(AcademyPresentationSlideRepository $academyPresentationSlides, ImageRepository $images)
    {
        $this->academyPresentationSlides = $academyPresentationSlides;
        $this->images = $images;
    }

    /**
     * Show the shop index page
     *
     * @return \Illuminate\Http\Response
     */
   
    
    public function show(AcademyPresentationSlide $slide)
    { 
        return view('site::academy-admin.presentation_slide.show', compact('slide'));
    }
    
    public function card(AcademyPresentationSlide $slide)
    { 
        return view('site::academy-admin.presentation_slide.card', compact('slide'));
    }

    public function create(AcademyPresentationSlideRequest $request)
    {
        $image = $this->getImage($request);
        $random = mt_rand(10000, 50000);
        $file_types = FileType::query()->where('group_id', 7)->orderBy('sort_order')->get();
        $file = $this->getFile($request);
        return view('site::academy-admin.presentation_slide.create', compact('image','random','file_types','file'));
    }



}