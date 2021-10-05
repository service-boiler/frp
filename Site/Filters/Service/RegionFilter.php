<?php

namespace ServiceBoiler\Prf\Site\Filters\Service;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;
use ServiceBoiler\Prf\Site\Models\Region;

class RegionFilter extends Filter
{
    /**
     * @var Region|null
     */
    private $region;

    function apply($builder, RepositoryInterface $repository)
    {
        if (!is_null($this->region)) {
            $builder = $builder->whereHas('addresses', function ($query) {
                $query->where('addresses.region_id', $this->region->id);
            });
        } else {
            $builder->whereRaw('false');
        }
        return $builder;
    }

    /**
     * @param Region $region
     * @return $this
     */
    public function setRegion(Region $region = null)
    {
        $this->region = $region;

        return $this;
    }
}