<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\EsbContract;

class EsbContractRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return EsbContract::class;
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
