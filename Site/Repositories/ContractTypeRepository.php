<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\ContractType;

class ContractTypeRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return ContractType::class;
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