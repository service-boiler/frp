<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\EsbRepair;

class EsbRepairRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return EsbRepair::class;
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
