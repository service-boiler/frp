<?php

namespace ServiceBoiler\Prf\Site\Filters\Order;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\SelectFilter;
use ServiceBoiler\Prf\Site\Models\Region;

class AddressRegionWarehouseSelectFilter extends SelectFilter
{
    use BootstrapSelect;

    protected $render = true;

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack() && !is_null($this->get($this->name()))) {
            $builder = $builder->whereHas('regions', function($region){
                $region->where('id', $this->get($this->name()));
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
        return Region::query()->where('country_id', config('site.country'))->orderBy('name')
            ->pluck('name', 'id')
            ->prepend(trans('site::messages.select_no_matter'), '')
            ->toArray();
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
        return 'region';
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