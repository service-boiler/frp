<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Http\Requests\DistributorSaleLoadRequest;
use ServiceBoiler\Prf\Site\Http\Requests\DistributorSaleUrlRequest;
use ServiceBoiler\Prf\Site\Models\DistributorSale;
use ServiceBoiler\Prf\Site\Models\User;

class DistributorSaleUrlController extends Controller
{

    use AuthorizesRequests;

	/**
	 * @param DistributorSaleUrlRequest $request
	 * @param DistributorSale $distributorSale
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
    public function store(DistributorSaleUrlRequest $request, DistributorSale $distributorSale, User $user)
    {
        //dd($user);
        //$this->authorize('view', $distributorSale);
        //dd($request->user());
        
        $distributorSale->updateFromUrl(['user'=>$user]);

        return redirect()->route('distributor-sales.index')->with('success', trans('site::distributor_sales.product_loaded'));

    }

	public function load(DistributorSaleLoadRequest $request, User $user)
	{
		$distributorSale->updateFromArray($request->check);
		return redirect()->route('distributor-sales.index')->with('success', trans('site::distributor_sales.product_loaded'));
    }

}