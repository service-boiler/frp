<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\Warehouse;

class WarehouseRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Warehouse::class;
    }

    /**
     * @return array
     */
    public function track():array
    {
        return [];
    }
}