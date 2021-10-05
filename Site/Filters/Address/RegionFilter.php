<?php

namespace ServiceBoiler\Prf\Site\Filters\Address;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class RegionFilter extends Filter
{
    /**
     * @var string|null
     */
    private $region_id;

    function apply($builder, RepositoryInterface $repository)
    {
        if (!is_null($this->region_id)) {
			if($this->region_id == 'RU-MOW' OR $this->region_id == 'RU-MOS')
                $builder->wherein('region_id',['RU-MOS','RU-MOW']);
			elseif($this->region_id == 'RU-LEN' OR $this->region_id == 'RU-SPE')
                $builder->wherein('region_id',['RU-LEN','RU-SPE']);
            elseif($this->region_id == 'RU-AD' OR $this->region_id == 'RU-KDA')
                $builder->wherein('region_id',['RU-AD','RU-KDA']);
                        else
	            $builder->where('region_id', $this->region_id);
			
        }

        return $builder;
    }

    /**
     * @param string $region_id
     * @return $this
     */
    public function setRegionId($region_id = null)
    {
        $this->region_id = $region_id;

        return $this;
    }
}
