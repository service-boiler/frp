<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\AcademyVideo\SearchFilter;
use ServiceBoiler\Prf\Site\Http\Resources\AcademyVideoCollection;
use ServiceBoiler\Prf\Site\Models\AcademyVideo;
use ServiceBoiler\Prf\Site\Repositories\AcademyVideoRepository;

class AcademyVideoController extends Controller
{
    protected $academyVideos;

    /**
     * Create a new controller instance.
     *
     * @param AcademyVideoRepository $academyVideos
     */
    public function __construct(AcademyVideoRepository $academyVideos)
    {
        $this->academyVideos = $academyVideos;
    }

    /**
     * @return AcademyVideoCollection
     */
    public function index()
    {   
        
        $this->academyVideos->applyFilter(new SearchFilter());
        
        return new AcademyVideoCollection($this->academyVideos->all());
    }

    /**
     * @param AcademyVideo $academyVideo
     * @return \Illuminate\View\View
     */
    public function create(AcademyVideo $video)
    {
        return view('site::academy-admin.stage-video.create', compact('video'));
    } 
   
}