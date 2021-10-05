<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\AcademyQuestion\SearchFilter;
use ServiceBoiler\Prf\Site\Http\Resources\AcademyQuestionCollection;
use ServiceBoiler\Prf\Site\Models\AcademyQuestion;
use ServiceBoiler\Prf\Site\Repositories\AcademyQuestionRepository;

class AcademyQuestionController extends Controller
{
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
     * @return AcademyQuestionCollection
     */
    public function index()
    {   
        
        $this->academyQuestions->applyFilter(new SearchFilter());
        
        return new AcademyQuestionCollection($this->academyQuestions->all());
    }

    /**
     * @param AcademyQuestion $academyQuestion
     * @return \Illuminate\View\View
     */
    public function create(AcademyQuestion $question)
    {
        return view('site::academy-admin.test-question.create', compact('question'));
    } 
    public function createAdmin(AcademyQuestion $academyQuestion)
    {   
        return view('site::academy-admin.test-question.create_admin', compact('academyQuestion'));
    }
}