<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\AuthorizationType;

class AuthorizationTypeRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return AuthorizationType::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [];
    }
}