<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\CurrencyArchive\CurrencyArchivePerPageFilter;
use ServiceBoiler\Prf\Site\Filters\Mounting\CurrencyArchiveDateFilter;
use ServiceBoiler\Prf\Site\Repositories\CurrencyArchiveRepository;

class CurrencyArchiveController extends Controller
{
    /**
     * @var CurrencyArchiveRepository
     */
    private $archives;

    /**
     * Create a new controller instance.
     * @param CurrencyArchiveRepository $archives
     */
    public function __construct(CurrencyArchiveRepository $archives)
    {

        $this->archives = $archives;
    }

    /**
     * Show the shop index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->archives->trackFilter();
        $repository = $this->archives;
        $archives = $this->archives->paginate($request->input('filter.per_page', config('site.per_page.archive', 25)), ['currency_archives.*']);
        return view('site::admin.archive.index', compact('archives', 'repository'));
    }

}