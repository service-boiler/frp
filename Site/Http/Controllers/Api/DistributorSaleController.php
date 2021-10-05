<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Jobs\ProcessDistributorSaleUrl;
use ServiceBoiler\Prf\Site\Models\DistributorSale;

class DistributorSaleController extends Controller
{

	public function cron()
	{
        
		/** @var Builder $distributorSale */
		if (($distributorSale = DistributorSale::uploadRequired())->exists()) {
			
            ProcessDistributorSaleUrl::dispatch($distributorSale->first());
		}

	}

}
