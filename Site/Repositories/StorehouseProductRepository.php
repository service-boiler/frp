<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\StorehouseProduct;

class StorehouseProductRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return StorehouseProduct::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [];
    }
}