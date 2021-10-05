<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\AcademyPresentation\SearchFilter;
use ServiceBoiler\Prf\Site\Http\Resources\AcademyPresentationCollection;
use ServiceBoiler\Prf\Site\Models\AcademyPresentation;
use ServiceBoiler\Prf\Site\Repositories\AcademyPresentationRepository;

class AcademyPresentationController extends Controller
{
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
     * @return AcademyPresentationCollection
     */
    public function index()
    {   
        
        $this->academyPresentations->applyFilter(new SearchFilter());
        
        return new AcademyPresentationCollection($this->academyPresentations->all());
    }

    /**
     * @param AcademyPresentation $academyPresentation
     * @return \Illuminate\View\View
     */
    public function create(AcademyPresentation $presentation)
    {
        return view('site::academy-admin.stage-presentation.create', compact('presentation'));
    } 
   
}