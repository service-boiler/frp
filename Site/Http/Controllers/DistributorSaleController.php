<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use ServiceBoiler\Prf\Site\Exports\Excel\DistributorSaleExcelUser;

use ServiceBoiler\Prf\Site\Filters\DistributorSale\EquipmentFilter;
use ServiceBoiler\Prf\Site\Filters\DistributorSale\DistributorSaleDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\DistributorSale\DistributorSaleBelongsUserFilter;

use ServiceBoiler\Prf\Site\Http\Requests\DistributorSaleRequest;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\DistributorSaleWeek;
use ServiceBoiler\Prf\Site\Models\DistributorSale;
use ServiceBoiler\Prf\Site\Models\DistributorSaleLog;
use ServiceBoiler\Prf\Site\Repositories\DistributorSaleRepository;
use Illuminate\Http\Request;

class DistributorSaleController extends Controller
{

    use AuthorizesRequests;

    /**
     * @var DistributorSaleRepository
     */
    protected $distributorSales;
    /**
     * @var DistributorSaleProductRepository
     */
    protected $products;

    /**
     * Create a new controller instance.
     *
     * @param DistributorSaleRepository $distributorSales
     * @param DistributorSaleProductRepository $products
     */
    public function __construct(DistributorSaleRepository $distributorSales)
    {
        $this->distributorSales = $distributorSales;
        
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
                 
        $this->distributorSales->trackFilter();
        $this->distributorSales->applyFilter(new DistributorSaleBelongsUserFilter());
       
         
        $user = Auth::user();
        $weeks = DistributorSaleWeek::query()->where('date_from','>',Carbon::now()->subMonth(3))->where('date_from','<',Carbon::now())->orderByDesc('date_from')->get();
        $allweeks = DistributorSaleWeek::query()->where('date_from','<',Carbon::now())->orderByDesc('date_from')->get();
      
        //dd($user->distributorSalesMonth('8',$this->distributorSales));
        
        return view('site::distributor_sales.index', [
            'repository'  => $this->distributorSales,
            'distributorSales' => $this->distributorSales->paginate($request->input('filter.per_page', config('site.per_page.sales', 10000)), ['distributor_sales.*']),
            'user' => $user,
            'weeks' => $weeks,
            'allweeks' => $allweeks
            
        ]);
    }

	/**
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 */
	public function excel()
	{

		$this->distributor_sales->applyFilter(new DistributorSaleUserExcelFilter());
		(new DistributorSaleExcelUser())->setRepository($this->distributor_sales)->render();

	}

    
    public function urlUpdate(Request $request, User $user)
    {
        if($request->input('url')) {
            $user->distributorSaleUrl()->updateOrCreate(['user_id'=>$user->id],['url'=>$request->input('url'), 'enabled'  => $request->filled('enabled')]);
        } else {$user->distributorSaleUrl()->delete();}
        
        return redirect()->route('distributor-sales.index')->with('success', trans('site::distributor_sales.url_updated'));

    }

}