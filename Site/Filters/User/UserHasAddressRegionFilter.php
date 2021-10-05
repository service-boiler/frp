<?php

namespace ServiceBoiler\Prf\Site\Filters\User;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class UserHasAddressRegionFilter extends Filter
{
    private $region_id;
    
    function apply($builder, RepositoryInterface $repository)
    {
        if (!is_null($this->region_id)) {
        $builder = $builder
            ->whereHas("addresses", function($query){
                if($this->region_id == 'RU-MOW' OR $this->region_id == 'RU-MOS')
                    $query->wherein('region_id',['RU-MOS','RU-MOW']);
                elseif($this->region_id == 'RU-LEN' OR $this->region_id == 'RU-SPE')
                    $query->wherein('region_id',['RU-LEN','RU-SPE']);
                elseif($this->region_id == 'RU-AD' OR $this->region_id == 'RU-KDA')
                    $query->wherein('region_id',['RU-AD','RU-KDA']);
                else
                $query->where('region_id',$this->region_id);
                
                $query->where('type_id','2')
                        ->where('approved','1');
            
            });
        }
        
        return $builder;
    }
    
    public function setRegionId($region_id = null)
    {
        $this->region_id = $region_id;

        return $this;
    }
}