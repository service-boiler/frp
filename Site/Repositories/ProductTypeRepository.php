<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\ProductType\SortFilter;
use ServiceBoiler\Prf\Site\Models\ProductType;

class ProductTypeRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return ProductType::class;
    }

    /**
     * @return array
     */
    public function track():array
    {
        return [
            SortFilter::class
        ];
    }
}