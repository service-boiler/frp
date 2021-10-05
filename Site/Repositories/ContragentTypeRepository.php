<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\ContragentType;

class ContragentTypeRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return ContragentType::class;
    }
    /**
     * @return array
     */
    public function track(): array
    {
        return [];
    }
}