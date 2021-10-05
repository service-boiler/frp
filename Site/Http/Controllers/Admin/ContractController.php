<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\Contract\ContractPerPageFilter;
use ServiceBoiler\Prf\Site\Filters\Contract\ContractSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Contract\ContractTypeSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Contract\ContractUserSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Contract\FerroliManagersContractFilter;
use ServiceBoiler\Prf\Site\Models\Contract;
use ServiceBoiler\Prf\Site\Repositories\ContractRepository;

class ContractController extends Controller
{

    protected $contracts;

    /**
     * @param ContractRepository $contracts
     */
    public function __construct(ContractRepository $contracts)
    {
        $this->contracts = $contracts;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $this->contracts->trackFilter();
        $this->contracts->applyFilter(new FerroliManagersContractFilter());
        $this->contracts->pushTrackFilter(ContractSearchFilter::class);
        $this->contracts->pushTrackFilter(ContractTypeSelectFilter::class);
        $this->contracts->pushTrackFilter(ContractUserSelectFilter::class);
        $this->contracts->pushTrackFilter(ContractPerPageFilter::class);

        return view('site::admin.contract.index', [
            'repository' => $this->contracts,
            'contracts'  => $this->contracts->paginate($request->input('filter.per_page', config('site.per_page.contract', 10)), ['contracts.*'])
        ]);
    }

    /**
     * @param Contract $contract
     * @return \Illuminate\Http\Response
     */
    public function show(Contract $contract)
    {
        return view('site::admin.contract.show', compact('contract'));
    }


}