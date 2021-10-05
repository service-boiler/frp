<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Jobs\ProcessStorehouseUrl;
use ServiceBoiler\Prf\Site\Models\Storehouse;

class StorehouseController extends Controller
{

	public function cron()
	{

		/** @var Builder $storehouse */
		if (($storehouse = Storehouse::uploadRequired())->exists()) {
			ProcessStorehouseUrl::dispatch($storehouse->first());
		}

	}

}
