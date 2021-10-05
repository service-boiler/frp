<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use ServiceBoiler\Prf\Site\Filters\EsbContract\EsbContractBelongsUserFilter;
use ServiceBoiler\Prf\Site\Filters\EsbContract\EsbContractPerPageFilter;
use ServiceBoiler\Prf\Site\Filters\EsbContract\EsbContractUserSelectFilter;
use ServiceBoiler\Prf\Site\Http\Requests\ContractRequest;
use ServiceBoiler\Prf\Site\Http\Requests\EsbContractRequest;
use ServiceBoiler\Prf\Site\Models\Contract;
use ServiceBoiler\Prf\Site\Models\ContractType;
use ServiceBoiler\Prf\Site\Models\EsbContract;
use ServiceBoiler\Prf\Site\Models\EsbContractTemplate;
use ServiceBoiler\Prf\Site\Repositories\ContractRepository;
use ServiceBoiler\Prf\Site\Repositories\EsbContractRepository;


class EsbContractController extends Controller
{

    use AuthorizesRequests;
    private $esbContracts;

    public function __construct(EsbContractRepository $esbContracts)
    {
        $this->esbContracts = $esbContracts;

    }

    public function index(EsbContractRequest $request)
    {
        $this->esbContracts->trackFilter();
        $this->esbContracts->applyFilter(new EsbContractBelongsUserFilter());
        $this->esbContracts->pushTrackFilter(EsbContractPerPageFilter::class);
        $this->esbContracts->pushTrackFilter(EsbContractUserSelectFilter::class);

        return view('site::esb_contract.index', [
            'repository'     => $this->esbContracts,
            'esbContracts'      => $this->esbContracts->paginate($request->input('filter.per_page', config('site.per_page.contract', 10)), ['esb_contracts.*'])
        ]);
    }


    public function create(ContractType $esbContract_type)
    {

        $this->authorize('create', Contract::class);

        $date = Carbon::now()->format('d.m.Y');
        $contragents = auth()->user()->contragents;
        $max_id = DB::table('contracts')->max('id');
        $number = $esbContract_type->getAttribute('prefix') . (++$max_id);

        return view('site::contract.create', compact(
            'contract_type',
            'date',
            'number',
            'contragents'
        ));
    }

    /**
     * @param ContractRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(EsbContractRequest $request)
    {

        $input=$request->only('contract.number','contract.date','contract.date_from','contract.date_to','contract.template_id','contract.service_contragent_id','contract.service_id'
            ,'contract.esb_contragent_id','contract.client_user_id','contract.esb_contragent_id');

        $esbContract = $this->esbContracts->create($input['contract']);

        return redirect()->route('esb-contracts.edit', $esbContract)->with('success', trans('site::contract.updated'));
    }

    /**
     * @param Contract $esbContract
     * @return \Illuminate\Http\Response
     */
    public function show(EsbContract $esbContract)
    {

        //$esbContract->getAttributes();
        $this->authorize('view', $esbContract);

        return view('site::esb_contract.show', compact('esbContract'));
    }

    public function edit(EsbContract $esbContract)
    {

        $this->authorize('edit', $esbContract);
        $contragents = auth()->user()->company()->contragents;
        $templates=EsbContractTemplate::query()->where('enabled',1)->where(function ($q){$q->where('shared',1)->orWhere('user_id',auth()->user()->company()->id);})->get();
        return view('site::esb_contract.edit', compact('esbContract','templates', 'contragents'));
    }

    /**
     * @param  ContractRequest $request
     * @param  Contract $esbContract
     * @return \Illuminate\Http\Response
     */
    public function update(EsbContractRequest $request, EsbContract $esbContract)
    {
        $this->authorize('edit', $esbContract);
        $input=$request->only('contract.number','contract.date','contract.date_from','contract.date_to','contract.template_id','contract.service_contragent_id','contract.service_id'
            ,'contract.esb_contragent_id');
        $esbContract->update($input['contract']);
        if($request->input('contract_fields')){

            foreach ($esbContract->template->esbContractNonCalcFields() as $field){
                if((array_key_exists($field->id,$request->input('contract_fields')))){
                    $fields[$field->id]=['value'=>$request->input('contract_fields.'.$field->id)];
                }
            }
            $esbContract->fields()->sync($fields);
        }

        return redirect()->route('esb-contracts.show', $esbContract)->with('success', trans('site::contract.updated'));
    }

    /**
     * @param Contract $esbContract
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contract $esbContract)
    {

        $this->authorize('delete', $esbContract);
        if ($esbContract->delete()) {
            Session::flash('success', trans('site::contract.deleted'));
        } else {
            Session::flash('error', trans('site::contract.error.deleted'));
        }
        $json['redirect'] = route('contracts.index');

        return response()->json($json);
    }

}
