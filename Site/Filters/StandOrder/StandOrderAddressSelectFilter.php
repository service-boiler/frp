<?php

namespace ServiceBoiler\Prf\Site\Filters\StandOrder;

use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\Address;


class StandOrderAddressSelectFilter extends WhereFilter
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
		return Address::query()->where('type_id',6)->orderBy('id')->pluck('name', 'id')
			->prepend(trans('site::messages.select_no_matter'), '')->toArray();
		/**
		 * ->map(function ($item, $key) {
		 * return str_limit($item, 50);
		 * })
		 */
	}

	/**
	 * @return string
	 */
	public function name(): string
	{
		return 'warehouse_address_id';
	}

	/**
	 * @return string
	 */
	public function column(): string
	{

		return 'stand_orders.warehouse_address_id';

	}

	public function defaults(): array
	{
		return [''];
	}

	public function label()
	{
		return trans('site::stand_order.warehouse_address_id');
	}
}