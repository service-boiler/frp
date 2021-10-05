<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\StorehouseLog\StorehouseLogPerPageFilter;
use ServiceBoiler\Prf\Site\Models\Storehouse;
use ServiceBoiler\Prf\Site\Repositories\StorehouseLogRepository;

class StorehouseLogController extends Controller
{

    use AuthorizesRequests;

	/**
	 * @var StorehouseLogRepository
	 */
	private $storehouse_logs;

	/**
	 * Create a new controller instance.
	 *
	 * @param StorehouseLogRepository $storehouse_logs
	 */
    public function __construct(StorehouseLogRepository $storehouse_logs)
    {
	    $this->storehouse_logs = $storehouse_logs;
    }

	/**
	 * @param Storehouse $storehouse
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
    public function index(Storehouse $storehouse, Request $request)
    {
	    $this->storehouse_logs->trackFilter();
	    $this->storehouse_logs->setBuilder($storehouse->logs()->getQuery());
        $this->storehouse_logs->pushTrackFilter(StorehouseLogPerPageFilter::class);
        return view('site::storehouse.log.index', [
        	'storehouse' => $storehouse,
            'repository'  => $this->storehouse_logs,
            'storehouse_logs' => $this->storehouse_logs->paginate(
                $request->input('filter.per_page', config('site.per_page.storehouse_log', 10)),
                ['storehouse_logs.*']
            ),
        ]);
    }

}