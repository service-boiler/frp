<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Models\WebinarTheme;
use ServiceBoiler\Prf\Site\Models\Promocode;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\WebinarThemeRequest;
use ServiceBoiler\Prf\Site\Repositories\PromocodeRepository;
use ServiceBoiler\Prf\Site\Repositories\WebinarThemeRepository;

class WebinarThemeController extends Controller
{

    protected $webinarThemes;

    /**
     * Create a new controller instance.
     *
     * @param WebinarThemeRepository $webinarThemes
     */
    public function __construct(WebinarThemeRepository $webinarThemes, PromocodeRepository $promocodes)
    {
        $this->webinarThemes = $webinarThemes;
        $this->PromocodeRepository = $promocodes;
    }

    /**
     * Show the shop index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->webinarThemes->trackFilter();
        

        return view('site::admin.webinar_theme.index', [
            'repository' => $this->webinarThemes,
            'webinarThemes'  => $this->webinarThemes->paginate(config('site.per_page.webinar_theme', 100), ['webinar_themes.*'])
        ]);
    }

   public function create(WebinarThemeRequest $request)
    {   
        $promocodes = Promocode::query()->orderBy('name')->get();   
        return view('site::admin.webinar_theme.create',[
        'promocodes' => $promocodes
        
        ]);
    }
   
   
   public function store(WebinarThemeRequest $request)
    {
       
        $webinarTheme = $this->webinarThemes->create($request->input('webinarTheme'));

        if($request->input('_newwebinar')== 1) {
        $redirect = redirect()->route('admin.webinars.create')->with('theme_id', $webinarTheme->id);
        } else {
        $redirect = redirect()->route('admin.webinar-themes.index')->with('success', trans('site::admin.webinar_theme.created'));
        }
        return $redirect;
    }
    
  /**
     * @param WebinarTheme $webinarTheme
     * @return \Illuminate\Http\Response
     */
    public function edit(WebinarTheme $webinarTheme)
    {   
        $promocodes = Promocode::query()->orderBy('name')->get();     
        return view('site::admin.webinar_theme.edit', compact('webinarTheme','promocodes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  WebinarThemeRequest $request
     * @param  WebinarTheme $webinarTheme
     * @return \Illuminate\Http\Response
     */
    public function update(WebinarThemeRequest $request, WebinarTheme $webinarTheme)
    {
       
        $webinarTheme->update($request->input('webinarTheme'));
        
        if($request->input('_newwebinar')== 1) {
        $redirect = redirect()->route('admin.webinars.create')->with('webinarTheme', $webinarTheme);
        } else {
        $redirect = redirect()->route('admin.webinar-themes.edit', $webinarTheme)->with('success', trans('site::admin.webinar_theme.updated'));
        }
        
        return $redirect;
    }
    
      public function destroy(webinarTheme $webinarTheme)
    {

        if ($webinarTheme->delete()) {
            return redirect()->route('admin.webinarThemes.index')->with('success', trans('site::admin.webinar_theme.deleted'));
        } else {
            return redirect()->route('admin.webinarThemes.show', $webinarTheme)->with('error', trans('site::admin.webinar_theme.error_deleted'));
        }
    }

 
}