<?php

namespace ServiceBoiler\Prf\Site\Filters\Order;

use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\OrderStatus;

class OrderInStockTypeSelectFilter extends WhereFilter
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
		return collect(trans('site::order.help.in_stock_type', []))
			->prepend(trans('site::messages.select_no_matter'), '')
			->toArray();
	}

	/**
	 * @return string
	 */
	public function name(): string
	{
		return 'in_stock_type';
	}

	/**
	 * @return string
	 */
	public function column(): string
	{

		return 'orders.in_stock_type';

	}

	public function defaults(): array
	{
		return [''];
	}

	public function label()
	{
		return trans('site::order.in_stock_type');
	}
}