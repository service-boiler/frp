<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\AcademyProgramRequest;
use ServiceBoiler\Prf\Site\Models\AcademyProgram;
use ServiceBoiler\Prf\Site\Models\AcademyStage;
use ServiceBoiler\Prf\Site\Models\AcademyTheme;
use ServiceBoiler\Prf\Site\Repositories\AcademyProgramRepository;


class AcademyProgramController extends Controller
{

    use AuthorizesRequests;

    protected $academyPrograms;

    /**
     * Create a new controller instance.
     *
     * @param AcademyProgramRepository $academyPrograms
     */
    public function __construct(AcademyProgramRepository $academyPrograms)
    {
        $this->academyPrograms = $academyPrograms;
    }

    /**
     * Show the user profile
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return view('site::academy-admin.program.index', [
            'repository' => $this->academyPrograms,
            'programs' => $this->academyPrograms->paginate($request->input('filter.per_page', config('site.per_page.academyProgram', 10)), ['academy_programs.*'])
        ]);
    }

    public function show(AcademyProgram $program)
    {
        $stages = $program->stages()->orderBy('sort_order', 'asc')->get();
        return view('site::academy-admin.program.show', compact('program','stages'));
    }

	/**
	 * @param FileRequest $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function create(AcademyProgramRequest $request)
    {
        //$this->authorize('create', AcademyProgram::class);
        $stages = collect([]);
        $themes = AcademyTheme::query()->get();
        return view('site::academy-admin.program.create', compact('themes','stages'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AcademyProgramRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AcademyProgramRequest $request)
    {
          
        $program = $this->academyPrograms->create(array_merge(
                $request->input(['program']),
                [
                    'enabled'     => $request->filled('program.enabled')
                ]
                ));
        if(!empty($request->input('stages'))) {
        $program->attachStages($request->input('stages'));
        }
        
        return redirect()->route('academy-admin.programs.show', $program)->with('success', trans('site::academy.program.created'));
    }

    public function edit(AcademyProgram $program)
    {
        $stages = $program->stages()->orderBy('sort_order', 'asc')->get();
        $themes = AcademyTheme::query()->get();
        
        return view('site::academy-admin.program.edit', compact('program','themes','stages'));
    }

    public function update(AcademyProgramRequest $request, AcademyProgram $program)
    {
        $program->update(array_merge(
                $request->input(['program']),
                [
                    'enabled'     => $request->filled('program.enabled')
                ]
                ));
        
        if($request->input('stages')) {
        $program->syncStages($request->input('stages'));
        } else {
           $program->detachStages($program->stages->toArray());     
        }
        
      
        return redirect()->route('academy-admin.programs.show', $program)->with('success', trans('site::academy.program.updated'));
    }
    
    public function destroy(AcademyProgram $program)
    {

        if ($program->delete()) {
            return redirect()->route('academy-admin.programs.index')->with('success', trans('site::academy-admin.program_deleted'));
        } else {
            return redirect()->route('academy-admin.programs.show', $program)->with('error', trans('site::academy-admin.program_error_deleted'));
        }
    }


}