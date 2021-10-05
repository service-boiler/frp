<?php

namespace ServiceBoiler\Prf\Site\Filters\UserPrereg;

use Illuminate\Support\Facades\DB;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\Region;

class RegionFilter extends WhereFilter
{
    use BootstrapSelect;

    protected $render = true;

   
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

    /**
     * Get the evaluated contents of the object.
     *
     * @return array
     */
    public function options(): array
    {
        return Region::whereHas('user_preregs', function ($query) {
            $query->whereNotNull('region_id');
        })
            ->orderBy('regions.name')
            ->pluck('name', 'id')
            ->prepend(trans('site::address.help.region_defaults'), '')
            ->toArray();
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