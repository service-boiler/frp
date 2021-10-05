<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\Storehouse;

class StorehouseRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Storehouse::class;
    }

    /**
     * @return array
     */
    public function track():array
    {
        return [];
    }
}