<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\EsbContractType;

class EsbContractTypeRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return EsbContractType::class;
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
