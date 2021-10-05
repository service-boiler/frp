<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\AcademyTestRequest;
use ServiceBoiler\Prf\Site\Models\AcademyTest;
use ServiceBoiler\Prf\Site\Models\AcademyTheme;
use ServiceBoiler\Prf\Site\Models\AcademyQuestion;
use ServiceBoiler\Prf\Site\Repositories\AcademyTestRepository;


class AcademyTestController extends Controller
{

    use AuthorizesRequests;

    protected $academyTests;

    /**
     * Create a new controller instance.
     *
     * @param AcademyTestRepository $academyTests
     */
    public function __construct(AcademyTestRepository $academyTests)
    {
        $this->academyTests = $academyTests;
    }

    /**
     * Show the user profile
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return view('site::academy-admin.test.index', [
            'repository' => $this->academyTests,
            'academyTests' => $this->academyTests->paginate($request->input('filter.per_page', config('site.per_page.academyTest', 100)), ['academy_tests.*'])->sortBy('name')
        ]);
    }

    public function show(AcademyTest $test)
    {
        return view('site::academy-admin.test.show', compact('test'));
    }

	/**
	 * @param FileRequest $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function create(AcademyTestRequest $request)
    {
        //$this->authorize('create', AcademyTest::class);
        $questions = collect([]);
        $themes = AcademyTheme::query()->get();
        $questionList = AcademyQuestion::query()->orderBy('text')->get();
        return view('site::academy-admin.test.create', compact('themes','questions','questionList'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AcademyTestRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AcademyTestRequest $request)
    {

        $test = $this->academyTests->create($request->input(['academyTest']));
        if($request->input('questions')) {
        $test->attachQuestions($request->input('questions'));
        }
        
        if($request->input('questionList')) {
        $test->attachQuestions($request->input('questionList'));
        }

        return redirect()->route('academy-admin.tests.edit', $test)->with('success', trans('site::academy.test.created'));
    }

    public function edit(AcademyTest $test)
    {
        $academyTest=$test;
        $questions = collect([]);
        $questions = $test->questions->sortBy('text');
        $themes = AcademyTheme::query()->get();
        $questionList = AcademyQuestion::query()->orderBy('text')->get();
        
        return view('site::academy-admin.test.edit', compact('academyTest','questions','themes','questionList'));
    }

    public function update(AcademyTestRequest $request, AcademyTest $test)
    {   
        $test->update($request->input('academyTest'));
                
        if($request->input('questions')) {
            $test->syncQuestions(array_values(array_unique($request->input('questions'))));
        }
        if($request->input('questionList')) {
        $test->attachQuestions($request->input('questionList'));
        }
        
        if($test->questions->count() < $test->count_questions) { $test->update(['count_questions'=>$test->questions->count()]);}
        if($test->questions->count() < $test->count_correct) { $test->update(['count_correct'=>$test->questions->count()]);}
        
        if(count(array_unique($request->input('questions')))< count($request->input('questions'))) {
            return redirect()->route('academy-admin.tests.edit', $test)->with('error', 'Тест обновлен. Внимание! Были удалены дубли вопросов.');
        } else {
            return redirect()->route('academy-admin.tests.edit', $test)->with('success', trans('site::academy.test.updated'));
        }
    }
    
    public function destroy(AcademyTest $test)
    {

        if ($test->delete()) {
            return redirect()->route('academy-admin.tests.index')->with('success', trans('site::academy-admin.test_deleted'));
        } else {
            return redirect()->route('academy-admin.tests.show', $test)->with('error', trans('site::academy-admin.test_error_deleted'));
        }
    }


}