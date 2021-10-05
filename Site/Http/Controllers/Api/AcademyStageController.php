<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\AcademyStage\SearchFilter;
use ServiceBoiler\Prf\Site\Http\Resources\AcademyStageCollection;
use ServiceBoiler\Prf\Site\Models\AcademyStage;
use ServiceBoiler\Prf\Site\Repositories\AcademyStageRepository;

class AcademyStageController extends Controller
{
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
     * @return AcademyStageCollection
     */
    public function index()
    {   
        
        $this->academyStages->applyFilter(new SearchFilter());
        
        return new AcademyStageCollection($this->academyStages->all());
    }

    /**
     * @param AcademyStage $academyStage
     * @return \Illuminate\View\View
     */
    public function create(AcademyStage $stage)
    {
        return view('site::academy-admin.program-stage.create', compact('stage'));
    } 
   
}