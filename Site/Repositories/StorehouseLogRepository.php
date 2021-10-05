<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\StorehouseLog;

class StorehouseLogRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return StorehouseLog::class;
    }

    /**
     * @return array
     */
    public function track():array
    {
        return [];
    }
}