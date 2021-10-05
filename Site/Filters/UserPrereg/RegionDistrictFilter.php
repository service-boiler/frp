<?php

namespace ServiceBoiler\Prf\Site\Filters\UserPrereg;

use Illuminate\Support\Facades\DB;
use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\Region;
use ServiceBoiler\Prf\Site\Models\District;

class RegionDistrictFilter extends WhereFilter
{
    use BootstrapSelect;

    protected $render = true;

    function apply($builder, RepositoryInterface $repository)
    {   
        if ($this->canTrack() && !is_null($district_id = $this->get($this->name()))) {
        
            $builder = $builder->whereHas('region', function ($query) use ($district_id) {
                $query->whereDistrictId( $district_id);
        
            });
        }

        return $builder;
    }
    /**
     * @return string
     */
    public function name(): string
    {
        return 'district_id';
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
        return District::whereHas('regions', function ($query) {
                $query->whereHas('user_preregs', function ($query) {
            $query->whereNotNull('region_id');
        });
        })
            ->orderBy('districts.name')
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
        return 'Округ';
    }
}