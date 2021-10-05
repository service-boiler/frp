<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\DistributorSale\DistributorSaleEquipmentFilter;
use ServiceBoiler\Prf\Site\Filters\DistributorSale\DistributorSaleProductGroupFilter;
use ServiceBoiler\Prf\Site\Filters\DistributorSale\DistributorSaleProductGroupTypeFilter;
use ServiceBoiler\Prf\Site\Filters\DistributorSale\DistributorSaleDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\DistributorSale\DistributorSaleDateToFilter;
use ServiceBoiler\Prf\Site\Filters\DistributorSale\DistributorSaleYearFilter;
use ServiceBoiler\Prf\Site\Models\DistributorSale;

class DistributorSaleRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return DistributorSale::class;
    }
    
    /**
     * @return array
     */
    public function track(): array
    {  
        return [
        //DistributorSaleDateFromFilter::class,
        //DistributorSaleDateToFilter::class,
        DistributorSaleYearFilter::class,
        DistributorSaleEquipmentFilter::class,
        DistributorSaleProductGroupFilter::class,
        DistributorSaleProductGroupTypeFilter::class,
        
        ];
    }
}