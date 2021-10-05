<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\AcademyStageRequest;
use ServiceBoiler\Prf\Site\Models\AcademyStage;
use ServiceBoiler\Prf\Site\Models\AcademyTest;
use ServiceBoiler\Prf\Site\Models\AcademyTheme;
use ServiceBoiler\Prf\Site\Models\AcademyQuestion;
use ServiceBoiler\Prf\Site\Repositories\AcademyStageRepository;


class AcademyStageController extends Controller
{

    use AuthorizesRequests;

    protected $academyStages;

    /**
     * Create a new controller instance.
     *
     * @param AcademyStageRepository $academyStages
     */
    public function __construct(AcademyStageRepository $academyStages)
    {
        $this->academyStages = $academyStages;
    }

    /**
     * Show the user profile
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return view('site::academy-admin.stage.index', [
            'repository' => $this->academyStages,
            'stages' => $this->academyStages->paginate($request->input('filter.per_page', config('site.per_page.academyStage', 100)), ['academy_stages.*'])->sortBy('name')
        ]);
    }

    public function show(AcademyStage $stage)
    {
        return view('site::academy-admin.stage.show', compact('stage'));
    }

	/**
	 * @param FileRequest $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function create(AcademyStageRequest $request)
    {
        //$this->authorize('create', AcademyStage::class);
        $presentations = collect([]);
        $videos = collect([]);
        $themes = AcademyTheme::query()->get();
        $tests = AcademyTest::query()->get();
        $stages = AcademyStage::query()->get();
        
        return view('site::academy-admin.stage.create', compact('themes','presentations','videos','tests','stages'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AcademyStageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AcademyStageRequest $request)
    {
           
        $stage = $this->academyStages->create(array_merge(
                $request->input(['stage']),
                [
                    'is_required'     => $request->filled('stage.is_required')
                ]
                ));
        if(!empty($request->input('presentations'))) {
        $stage->attachPresentations($request->input('presentations'));
        }
        if(!empty($request->input('videos'))) {
        $stage->attachVideos($request->input('videos'));
        }
        if(!empty($request->input('stage.test_id'))) {
        $stage->attachTest($request->input('stage.test_id'));
        }
        return redirect()->route('academy-admin.stages.edit', $stage)->with('success', trans('site::academy.stage.created'));
    }

    public function edit(AcademyStage $stage)
    {
       
        $presentations = collect([]);
        $videos = collect([]);
        $presentations = $stage->presentations;
        $themes = AcademyTheme::query()->get();
        $tests = AcademyTest::query()->get();
        $stages = AcademyStage::query()->get();
       
        
        return view('site::academy-admin.stage.edit', compact('stage','themes','tests','stages'));
    }

    public function update(AcademyStageRequest $request, AcademyStage $stage)
    {   
        $stage->update(array_merge(
                $request->input(['stage']),
                [
                    'is_required'     => $request->filled('stage.is_required')
                ]
                ));
        
        if($request->input('presentations')) {
        $stage->syncPresentations($request->input('presentations'));
        } else {
           $stage->detachPresentations($stage->presentations->toArray());     
        }
        
        if($request->input('videos')) {
        $stage->syncVideos($request->input('videos'));
        } else {
           $stage->detachVideos($stage->videos->toArray());     
        }
        if($request->input('stage.test_id')) {
        $stage->syncTests($request->input('stage.test_id'));
        }

        return redirect()->route('academy-admin.stages.show', $stage)->with('success', trans('site::academy.stage.updated'));
    }
    
    public function destroy(AcademyStage $stage)
    {

        if ($stage->delete()) {
            return redirect()->route('academy-admin.stages.index')->with('success', trans('site::academy-admin.stage_deleted'));
        } else {
            return redirect()->route('academy-admin.stages.show', $stage)->with('error', trans('site::academy-admin.stage_error_deleted'));
        }
    }


}