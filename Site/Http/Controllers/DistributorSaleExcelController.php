<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use ServiceBoiler\Prf\Site\Http\Requests\DistributorSaleExcelRequest;
use ServiceBoiler\Prf\Site\Models\DistributorSale;
use ServiceBoiler\Prf\Site\Models\DistributorSaleWeek;
use ServiceBoiler\Prf\Site\Repositories\DistributorSaleWeekRepository;

class DistributorSaleExcelController extends Controller
{

    use AuthorizesRequests;
    
    public function __construct(DistributorSaleWeekRepository $distributorSaleWeeks)
    {
        $this->distributorSaleWeeks = $distributorSaleWeeks;
        
    }

	/**
	 * @param DistributorSaleExcelRequest $request
	 * @param DistributorSale $distributorSale
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
	 */
    public function store(DistributorSaleExcelRequest $request, DistributorSale $distributorSale)
    {
        //$this->authorize('view', $distributorSale);
        $week=DistributorSaleWeek::find($request->week_id);
       
        
	    $distributorSale->updateFromExcel($request->path, $request->user(), $week);


        return redirect()->route('distributor-sales.index')->with('success', trans('site::storehouse.distributor.sales_loaded'));

    }

}