<?php

namespace ServiceBoiler\Prf\Site\Filters\User;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class UserMapFilter extends Filter
{

    /**
     * @var array|null
     */
    private $accepts;

    /**
     * @var null|int
     */
    private $role_id;

    function apply($builder, RepositoryInterface $repository)
    {
        $builder = $builder
            ->whereHas('authorization_accepts', function ($query) {
                if (!is_null($this->accepts) && !empty($this->accepts)) {
                    $query
                        ->join('authorization_types', 'authorization_types.id', '=', 'authorization_accepts.type_id')
                        ->where('authorization_types.brand_id', config('site.brand_default'))
                        ->where('authorization_accepts.role_id', $this->role_id)
                        ->whereIn('authorization_accepts.type_id', $this->accepts)
                        ;
                }
            })
            ->orderBy("region_id");
        
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

    /**
     * @param array $accepts
     * @return $this
     */
    public function setAccepts(array $accepts = null)
    {
        $this->accepts = $accepts;

        return $this;
    }

    /**
     * @param null $role_id
     * @return $this
          */
    public function setRoleId($role_id = null)
    {
        $this->role_id = $role_id;

        return $this;
    }

    public function setRegionId($region_id = null)
    {
        $this->region_id = $region_id;

        return $this;
    }

}