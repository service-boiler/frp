<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Models\AcademyVideo;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\AcademyVideoRequest;
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
     * Show the shop index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('site::academy-admin.video.index', [
            'repository' => $this->academyVideos,
            'videos'  => $this->academyVideos->paginate(config('site.per_page.academy_video', 100), ['academy_videos.*'])
        ]);
    }

   public function create(AcademyVideoRequest $request)
    {   
        return view('site::academy-admin.video.create');
    }
   
   
   public function store(AcademyVideoRequest $request)
    {
       
        $academyVideo = $this->academyVideos->create($request->input('video'));
        $redirect = redirect()->route('academy-admin.videos.index')->with('success', trans('site::academy.video.created'));
        
        return $redirect;
    }
    
  /**
     * @param AcademyVideo $academyVideo
     * @return \Illuminate\Http\Response
     */
    public function show(AcademyVideo $video)
    {   
        return view('site::academy-admin.video.show', compact('video'));
    }
    public function edit(AcademyVideo $video)
    {   
        return view('site::academy-admin.video.edit', compact('video'));
    }
   
    /**
     * Update the specified resource in storage.
     *
     * @param  AcademyVideoRequest $request
     * @param  AcademyVideo $academyVideo
     * @return \Illuminate\Http\Response
     */
    public function update(AcademyVideoRequest $request, AcademyVideo $video)
    {
       
        $video->update($request->input('video'));
        $redirect = redirect()->route('academy-admin.videos.show',$video)->with('success', trans('site::academy.video.updated'));
        
        return $redirect;
    }
    
      public function destroy(AcademyVideo $video)
    {

        if ($video->delete()) {
            return redirect()->route('academy-admin.videos.index')->with('success', trans('site::academy-admin.video.deleted'));
        } else {
            return redirect()->route('academy-admin.videos.show', $video)->with('error', trans('site::academy-admin.video.error_deleted'));
        }
    }

 
}