<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters;
use ServiceBoiler\Prf\Site\Filters\PathUrlFilter;
use ServiceBoiler\Prf\Site\Events\FeedbackCreateEvent;
use ServiceBoiler\Prf\Site\Http\Requests\FeedbackRequest;
use ServiceBoiler\Prf\Site\Repositories\UserRepository;
use ServiceBoiler\Prf\Site\Repositories\AnnouncementRepository;
use ServiceBoiler\Prf\Site\Repositories\HeadBannerBlockRepository;
use ServiceBoiler\Prf\Site\Models\File;
use Illuminate\Support\Facades\Storage;

class StaticPageController extends Controller
{
    private $users;

    private $headBannerBlocks;
    /**
     * Create a new controller instance.
     *
     * @param UserRepository $users
     */
    public function __construct(
        AnnouncementRepository $announcements, 
        UserRepository $users,
        HeadBannerBlockRepository $headBannerBlocks)
    {
        $this->announcements = $announcements;
		$this->users = $users;
        $this->headBannerBlocks = $headBannerBlocks;
    }
 
    /**
     * Show application index page
     *
     * @return \Illuminate\Http\Response
     */
    public function abouts()
    {
        return view('site::static.abouts');
    }

    /**
     * Show application index page
     *
     * @return \Illuminate\Http\Response
     */
    public function feedback()
    { 
        if(env('MIRROR_CONFIG')=='sfby'){
            return view('site::static.feedback_sfby');
        } else {
    
        return view('site::static.feedback');
        }
    }
    
    public function feedbackSuccess()
    {   
        return view('site::static.feedback_success');
        
    }
    
    public function pdFru()
    {
        return view('site::static.pd_fru');
    }
    
        public function pdFmru()
    {
        return view('site::static.pd_fmru');
    }


    public function masterplus_updates()
    {
        return view('site::static.masterplus_updates');
    }
	
	public function masterplus()
	{	
		$announcements = $this->announcements
            ->applyFilter(new Filters\Announcement\SortDateFilter())
            ->applyFilter(new Filters\Announcement\PublishedFilter())
            ->applyFilter(new Filters\Announcement\LimitThreeFilter())
            ->all(['announcements.*']);
				
        $headBannerBlocks = $this->headBannerBlocks
            ->applyFilter(new Filters\ShowFilter())
            ->applyFilter(new Filters\BySortOrderFilter())
            ->applyFilter(new Filters\PathUrlFilter())
            ->all(['head_banner_blocks.*']);
			
        return view('site::static.masterplus', compact('announcements','headBannerBlocks'));
    }
	
	public function managerplus()
	{	
		$announcements = $this->announcements
            ->applyFilter(new Filters\Announcement\SortDateFilter())
            ->applyFilter(new Filters\Announcement\PublishedFilter())
            ->applyFilter(new Filters\Announcement\LimitThreeFilter())
            ->all(['announcements.*']);
				
        $headBannerBlocks = $this->headBannerBlocks
            ->applyFilter(new Filters\ShowFilter())
            ->applyFilter(new Filters\BySortOrderFilter())
            ->applyFilter(new Filters\PathUrlFilter())
            ->all(['head_banner_blocks.*']);
			
        return view('site::static.managerplus', compact('announcements','headBannerBlocks'));
    }
	
	public function ferroliplus()
	{	
		$announcements = $this->announcements
            ->applyFilter(new Filters\Announcement\SortDateFilter())
            ->applyFilter(new Filters\Announcement\PublishedFilter())
            ->applyFilter(new Filters\Announcement\LimitThreeFilter())
            ->all(['announcements.*']);
				
        $headBannerBlocks = $this->headBannerBlocks
            ->applyFilter(new Filters\ShowFilter())
            ->applyFilter(new Filters\BySortOrderFilter())
            ->applyFilter(new Filters\PathUrlFilter())
            ->all(['head_banner_blocks.*']);
			
        return view('site::static.ferroliplus', compact('announcements','headBannerBlocks'));
    }

    public function message(FeedbackRequest $request)
    {   
       $rf = Storage::disk('missions')->put($request->input('email').'.txt', $request->only(['name', 'email','phone','theme','message','captcha']));
        event(new FeedbackCreateEvent($request->only(['name', 'email','phone','theme','message','captcha'])));

        
        
        
        return redirect()->route('feedback_success')->with('success', trans('Сообщение отправлено'));
    }

}
