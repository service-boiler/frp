<?php

namespace ServiceBoiler\Prf\Site\Filters\Mission;

use Illuminate\Support\Facades\DB;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\Region;

class MissionRegionFilter extends WhereFilter
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

    
    public function options(): array
    {
        return ['' => trans('site::messages.select_no_matter')] + Region::query()->has('missions')
                ->orderBy('name')->pluck('name', 'id')
                ->map(function ($item, $key) {
                    return str_limit($item, config('site.name_limit', 25));
                })->toArray();
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