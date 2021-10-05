<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Repositories\BankRepository;
use ServiceBoiler\Prf\Site\Models\Bank;

class BankController extends Controller
{

    protected $banks;

    /**
     * Create a new controller instance.
     *
     * @param BankRepository $banks
     */
    public function __construct(BankRepository $banks)
    {
        $this->banks = $banks;
    }

    /**
     * Show the user profile
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->banks->trackFilter();
        return view('site::admin.bank.index', [
            'repository' => $this->banks,
            'banks'      => $this->banks->paginate(config('site.per_page.bank', 10), ['banks.*'])
        ]);
    }

    public function show(Bank $bank)
    {
        return view('site::admin.bank.show', compact('bank'));
    }
}