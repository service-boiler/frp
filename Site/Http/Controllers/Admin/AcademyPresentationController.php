<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\AcademyPresentationRequest;
use ServiceBoiler\Prf\Site\Models\AcademyPresentation;
use ServiceBoiler\Prf\Site\Repositories\AcademyPresentationRepository;


class AcademyPresentationController extends Controller
{

    use AuthorizesRequests;

    protected $academyPresentations;

    /**
     * Create a new controller instance.
     *
     * @param AcademyPresentationRepository $academyPresentations
     */
    public function __construct(AcademyPresentationRepository $academyPresentations)
    {
        $this->academyPresentations = $academyPresentations;
    }

    /**
     * Show the user profile
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return view('site::academy-admin.presentation.index', [
            'repository' => $this->academyPresentations,
            'academyPresentations' => $this->academyPresentations->paginate($request->input('filter.per_page', config('site.per_page.academy_presentation', 100)), ['academy_presentations.*'])->sortBy('name')
        ]);
    }

    public function show(AcademyPresentation $presentation)
    {   
        return view('site::academy-admin.presentation.show', compact('presentation'));
    }
    
    public function preview(AcademyPresentation $presentation, $slide_num=0)
    {   
        return view('site::academy-admin.presentation.preview', compact('presentation','slide_num'));
    }

	/**
	 * @param FileRequest $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function create(AcademyPresentationRequest $request)
    {   
        
        return view('site::academy-admin.presentation.create');
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AcademyPresentationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AcademyPresentationRequest $request)
    {
        //dd($request->input('presentation_slide'));
        $presentation = $this->academyPresentations->create($request->input(['presentation']));
        if ($request->filled('slide')) {
            $presentation->slides()->createMany($request->input('slide'));
        }

        return redirect()->route('academy-admin.presentations.show',$presentation)->with('success', trans('site::academy.presentation.created'));
    }

    public function edit(AcademyPresentation $presentation)
    {   $answers=$presentation->answers;
        return view('site::academy-admin.presentation.edit', compact('presentation','answers'));
    }

    public function update(AcademyPresentationRequest $request, AcademyPresentation $presentation)
    {   
         $presentation->slides()->delete();
       
         if ($request->filled('slide')) {
            $presentation->slides()->createMany($request->input('slide'));
        }
        $presentation->update(array_merge(
            $request->input(['presentation']),
            [
                'enabled'           => $request->filled('presentation.enabled')
            ]
        ));
        
        return redirect()->route('academy-admin.presentations.show', $presentation)->with('success', trans('site::academy.presentation.updated'));
    }
    
    public function destroy(AcademyPresentation $presentation)
    {   
        if ($presentation->delete()) {
            $redirect = route('academy-admin.presentations.index');
        } else {
            $redirect = route('academy-admin.presentations.show', $presentation);
        }
        $json['redirect'] = $redirect;

        return response()->json($json);
    }


}