<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Concerns\StoreFiles;
use ServiceBoiler\Prf\Site\Http\Requests\EsbContractTemplateRequest;
use ServiceBoiler\Prf\Site\Models\EsbContractField;
use ServiceBoiler\Prf\Site\Models\EsbContractFieldRelation;
use ServiceBoiler\Prf\Site\Models\EsbContractTemplate;
use ServiceBoiler\Prf\Site\Repositories\EsbContractTemplateRepository;

class EsbContractTemplateController extends Controller
{
    use StoreFiles;
    /**
     * @var EsbContractTemplateRepository
     */
    protected $esbContractTemplates;

    /**
     * Create a new controller instance.
     *
     * @param EsbContractTemplateRepository $esbContractTemplates
     */
    public function __construct(EsbContractTemplateRepository $esbContractTemplates)
    {
        $this->esbContractTemplates = $esbContractTemplates;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $esbContractTemplates = $this->esbContractTemplates->all();

        return view('site::esb_contract_template.index', compact('esbContractTemplates'));
    }

    /**
     * @param EsbContractTemplateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(EsbContractTemplateRequest $request)
    {
        $file = $this->getFile($request);
        $presetFields=EsbContractField::where('preset',1)->get();

        return view('site::esb_contract_template.create', compact('file','presetFields'));
    }

    /**
     * @param EsbContractTemplate $esbContractTemplate
     * @return \Illuminate\Http\Response
     */
    public function show(EsbContractTemplate $esbContractTemplate)
    {
        return view('site::esb_contract_template.show', compact('esbContractTemplate'));
    }


    /**
     * @param EsbContractTemplateRequest $request
     * @param EsbContractTemplate $esbContractTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(EsbContractTemplateRequest $request, EsbContractTemplate $esbContractTemplate)
    {
        $file = $this->getFile($request, $esbContractTemplate);
        $presetFields=EsbContractField::where('preset',1)->get();

        return view('site::esb_contract_template.edit', compact('esbContractTemplate', 'file','presetFields'));
    }

    /**
     * @param  EsbContractTemplateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(EsbContractTemplateRequest $request)
    {

        $esbContractTemplate = $this->esbContractTemplates->create(array_merge(
            $request->input('esb_contract_template'),
            ['enabled' => $request->filled('esb_contract_template.enabled'),
             'shared' => $request->filled('esb_contract_template.shared'),
             'user_id' => $request->user()->id
            ]
        ));
        if($request->input('templfields')){

            foreach ($request->input('templfields') as $field){
                $esbContractTemplate->esbContractFields()->create([
                    'name'=>$field['name'],
                    'title'=>$field['title'],
                    'shared'=>0,
                    'user_id'=>$request->user()->id,
                ]);
            }

        }

        return redirect()->route('esb-contract-templates.show', $esbContractTemplate)->with('success', trans('site::user.esb_contract_template.created'));
    }

    /**
     * @param  EsbContractTemplateRequest $request
     * @param  EsbContractTemplate $esbContractTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(EsbContractTemplateRequest $request, EsbContractTemplate $esbContractTemplate)
    {
        $esbContractTemplate->update(array_merge(
            $request->input('esb_contract_template'),
            ['enabled' => $request->filled('esb_contract_template.enabled'),
                'shared' => $request->filled('esb_contract_template.shared')
            ]
        ));

        if($request->input('templfields')){

            foreach ($esbContractTemplate->esbContractFields as $field){
                //dd($field->id,$request->input('templfields'));
                if(!in_array($field->id,$request->input('templfields'))){
                    if(!EsbContractFieldRelation::query()->where('esb_contract_field_id',$field->id)->exists()){
                        $field->delete();
                    }
                }
            }

            foreach ($request->input('templfields') as $key=>$field){
                if($fieldtempl=$esbContractTemplate->esbContractFields()->find('12133')){
                    $fieldtempl->update([
                        'name'=>$field['name'],
                        'title'=>$field['title'],
                        'shared'=>0,
                        'user_id'=>$request->user()->id]);
                } else {
                    $esbContractTemplate->esbContractFields()->create(
                    [
                    'name'=>$field['name'],
                    'title'=>$field['title'],
                    'shared'=>0,
                    'user_id'=>$request->user()->id]);
                }
            }


        }
        return redirect()->route('esb-contract-templates.show', $esbContractTemplate)->with('success', trans('site::user.esb_contract_template.updated'));
    }
    public function destroy(EsbContractTemplate $esbContractTemplate)
    {
        $esbContractTemplate->delete();

        $redirect = route('esb-contract-templates.index');
        $json['redirect'] = $redirect;
        return response()->json($json);
    }

}