<?php
namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\EnabledFilter;
use ServiceBoiler\Prf\Site\Filters\PublicFilter;
use ServiceBoiler\Prf\Site\Models\RevisionPart;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\RevisionPartRepository;
use ServiceBoiler\Prf\Site\Http\Requests\RevisionPartRequest;

class RevisionPartController extends Controller
{
    
    protected $revisionParts;
    
    public function __construct(RevisionPartRepository $revisionParts)
    {
        $this->revisionParts = $revisionParts;
    }
    
    public function index(RevisionPartRequest $request)
    {  
        $this->revisionParts->trackFilter();
        $this->revisionParts->applyFilter(new EnabledFilter);
        $this->revisionParts->applyFilter(new PublicFilter);
        
        return view('site::revision_part.index', [
            'repository' => $this->revisionParts,
            'revisionParts'    => $this->revisionParts->paginate($request->input('filter.per_page', config('site.per_page.revisionParts', 100)), ['revision_parts.*'])
        ]);
    } 
    
    public function show(RevisionPart $revisionPart)
    {
        
        return  view('site::revision_part.show',compact('revisionPart'));
    
    
    }
   
    
}