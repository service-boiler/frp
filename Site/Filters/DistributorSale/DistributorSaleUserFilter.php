<?php

namespace ServiceBoiler\Prf\Site\Filters\DistributorSale;

use Illuminate\Support\Facades\DB;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\User;

class DistributorSaleUserFilter extends WhereFilter
{
    use BootstrapSelect;

    protected $render = true;

     public function options(): array
    {
        return ['' => trans('site::messages.select_no_matter')] + User::query()->has('distributorSales')
                ->orderBy('name')->pluck('name', 'id')
                ->map(function ($item, $key) {
                    return str_limit($item, config('site.name_limit', 125));
                })->toArray();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'user_id';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'user_id';

    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::distributor_sales.filter.user');
    }

}