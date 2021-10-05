<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use ServiceBoiler\Prf\Site\Filters\BelongsUserWithParentFilter;
use ServiceBoiler\Prf\Site\Models\EsbContract;
use ServiceBoiler\Prf\Site\Models\EsbContractType;
use ServiceBoiler\Prf\Site\Repositories\EsbContractTypeRepository;
use ServiceBoiler\Prf\Site\Repositories\EsbContractTemplateRepository;
use ServiceBoiler\Prf\Site\Http\Requests\EsbContractTypeRequest;

class EsbContractTypeController extends Controller
{

    use AuthorizesRequests;
    /**
     * @var FileTypeRepository
     */
    protected $file_types;
    /**
     * @var FileRepository
     */
    protected $files;
    
    
    public function __construct(EsbContractTypeRepository $esbContractTypes, EsbContractTemplateRepository $esbContractTemplates)
    {
        $this->esbContractTypes = $esbContractTypes;
        $this->esbContractTemplates = $esbContractTemplates;
        
    }
    
    public function index(EsbContractTypeRequest $request)
    {      
        
        
        $this->esbContractTypes->trackFilter();
        $this->esbContractTypes->applyFilter(new BelongsUserWithParentFilter());
       
        return view('site::esb_contract_type.index',[
            'repository' => $this->esbContractTypes,
            'esbContractTypes'    => $this->esbContractTypes->paginate($request->input('filter.per_page', 100), ['esb_contract_types.*'])
        ]);
    }
    
    public function show(EsbContractType $esbContractType)
    {
        
       
        return view('site::esb_contract_type.show', compact('esbContractType'));
    }
    
    
    public function create(esbContractTypeRequest $request)
    {     
        $this->esbContractTemplates->trackFilter();
        $templates = $this->esbContractTemplates->all();
        
        return view('site::esb_contract_type.create', compact('templates'));
        
    }
     
    public function edit(esbContractTypeRequest $request, EsbContractType $esbContractType)
    {   
        $this->esbContractTemplates->trackFilter();
        $templates = $this->esbContractTemplates->all();
        return view('site::esb_contract_type.edit', compact('esbContractType','templates'));
        
    }
    
    
    public function store(Request $request)
    {   
        $user=auth()->user();
        
            $owner=$user->parent ? $user->parent : $user;
        
        $esbContractType=$owner->esbContractTypes()->create(array_merge($request->only(
            'name', 
            'template_id' ,
            'color' ,
            'comments' ,
            'enabled' 
            )));
        if($user->hasPermission('admin_esb_super') || $user->admin) {
            $esbContractType->update(['shared'=>$request->input('shared')]);
        }
        return redirect()->route('esb-contract-types.show',$esbContractType)->with('success', trans('site::user.esb_contract_type.created'));
        
    }
    
    public function update(Request $request, EsbContractType $esbContractType)
    {   
        $user=auth()->user();
        $esbContractType->update($request->only(
            'name', 
            'template_id' ,
            'comments' ,
            'color' ,
            'enabled' 
            ));
        if($user->hasPermission('admin_esb_super') || $user->admin) {
            $esbContractType->update(['shared'=>$request->input('shared')]);
        }
        return redirect()->route('esb-contract-types.show',$esbContractType)->with('success', trans('site::user.esb_contract_type.updated'));
        
    }
    
    
   
    public function destroy(EsbContractType $esbContractType)
	{

		$this->authorize('delete', $esbContractType);
        $esbContractType->enabled=0;
        $esbContractType->deleted=1;
        $esbContractType->save();
        
		$redirect = route('esb-contract-types.index');

		$json['redirect'] = $redirect;

		return response()->json($json);

	}
    
    
}