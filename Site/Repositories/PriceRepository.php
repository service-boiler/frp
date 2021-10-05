<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
//use ServiceBoiler\Prf\Site\Filters\PriceSearchFilter;
//use ServiceBoiler\Prf\Site\Filters\PriceSortFilter;
use ServiceBoiler\Prf\Site\Models\Price;

class PriceRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Price::class;
    }

    /**
     * @return array
     */
    public function track():array
    {
        return [
            //PriceSortFilter::class,
            //PriceSearchFilter::class,
        ];
    }
}