<?php

namespace ServiceBoiler\Prf\Site\Filters\RetailOrder;

use Illuminate\Support\Facades\DB;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\Region;

class RetailOrderRegionFilter extends WhereFilter
{
    use BootstrapSelect;

    protected $render = true;

    function apply($builder, RepositoryInterface $repository)
    {

        if ($this->canTrack() && !is_null($this->get($this->name()))) {
            $builder = $builder->whereHas('userAddress', function($address){
                $address->where(DB::raw($this->column()), $this->operator(), $this->get($this->name()));
            });
        }

        return $builder;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return array
     */
    public function options(): array
    {
        return ['' => trans('site::messages.select_no_matter')] +
            Region::query()
                ->whereHas('addresses', function ($address){
                    $address->has('retailOrders');
                })
                ->orderBy('name')
                ->pluck('name', 'id')->toArray();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'region_id';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'region_id';

    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::address.region_id');
    }
}