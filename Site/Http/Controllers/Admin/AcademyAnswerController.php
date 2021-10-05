<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Repositories\AcademyAnswerRepository;

class AcademyAnswerController extends Controller
{
    /**
     * @var AcademyAnswerRepository
     */
    private $answers;

    /**
     * Create a new controller instance.
     *
     * @param AcademyAnswerRepository $answers
     */
    public function __construct(
        AcademyAnswerRepository $answers
    )
    {
        $this->answers = $answers;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $random = mt_rand(10000, 50000);
        
        return response()->view('site::academy-admin.answer.create', compact('random'));
    }


}