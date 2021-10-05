<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\DistributorSaleWeek;

class DistributorSaleWeekRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return DistributorSaleWeek::class;
    }
    
    /**
     * @return array
     */
    public function track(): array
    {  
        return [
        
        
        ];
    }
}