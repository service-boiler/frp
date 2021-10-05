<?php

namespace ServiceBoiler\Prf\Site\Filters\StandOrder;

use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\StandOrderStatus;

class StandOrderStatusFilter extends WhereFilter
{

	use BootstrapSelect;

	protected $render = true;

	/**
	 * Get the evaluated contents of the object.
	 *
	 * @return array
	 */
	public function options(): array
	{
		return StandOrderStatus::query()->whereHas('standOrders', function ($query) {
			if (auth()->user()->admin == 0) {
				$query->where('user_id', auth()->user()->getAuthIdentifier());
			}
		})
			->orderBy('id')
			->pluck('name', 'id')
			->prepend(trans('site::messages.select_no_matter'), '')
			->toArray();
	}

	/**
	 * @return string
	 */
	public function name(): string
	{
		return 'status_id';
	}

	/**
	 * @return string
	 */
	public function column(): string
	{

		return 'stand_orders.status_id';

	}

	public function defaults(): array
	{
		return [''];
	}

	public function label()
	{
		return trans('site::stand_order.status_id');
	}
}