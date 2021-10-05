<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\AcademyQuestionRequest;
use ServiceBoiler\Prf\Site\Models\AcademyQuestion;
use ServiceBoiler\Prf\Site\Repositories\AcademyQuestionRepository;


class AcademyQuestionController extends Controller
{

    use AuthorizesRequests;

    protected $academyQuestions;

    /**
     * Create a new controller instance.
     *
     * @param AcademyQuestionRepository $academyQuestions
     */
    public function __construct(AcademyQuestionRepository $academyQuestions)
    {
        $this->academyQuestions = $academyQuestions;
    }

    /**
     * Show the user profile
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return view('site::academy-admin.question.index', [
            'repository' => $this->academyQuestions,
            'academyQuestions' => $this->academyQuestions->paginate($request->input('filter.per_page', config('site.per_page.academy_question', 1000)), ['academy_questions.*'])->sortBy('text')
        ]);
    }

    public function show(AcademyQuestion $question)
    {
        return view('site::academy-admin.question.show', compact('question'));
    }

	/**
	 * @param FileRequest $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function create(AcademyQuestionRequest $request)
    {   
        
        return view('site::academy-admin.question.create');
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AcademyQuestionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AcademyQuestionRequest $request)
    {

        $question = $this->academyQuestions->create($request->input(['question']));
        if ($request->filled('answer')) {
            $question->answers()->createMany($request->input('answer'));
        }

        return redirect()->route('academy-admin.questions.index')->with('success', trans('site::academy.question.created'));
    }

    public function edit(AcademyQuestion $question)
    {   $answers=$question->answers;
        return view('site::academy-admin.question.edit', compact('question','answers'));
    }

    public function update(AcademyQuestionRequest $request, AcademyQuestion $question)
    {
         $question->answers()->delete();
       
         if ($request->filled('answer')) {
            $question->answers()->createMany($request->input('answer'));
        }
        $question->update(array_merge(
            $request->input(['question']),
            [
                'enabled'           => $request->filled('question.enabled')
            ]
        ));
        
        return redirect()->route('academy-admin.questions.edit', $question)->with('success', trans('site::academy.question.updated'));
    }
    
    public function destroy(AcademyQuestion $question)
    {

        if ($question->delete()) {
            return redirect()->route('academy-admin.questions.index')->with('success', trans('site::academy-admin.question_deleted'));
        } else {
            return redirect()->route('academy-admin.questions.show', $question)->with('error', trans('site::academy-admin.question_error_deleted'));
        }
    }


}