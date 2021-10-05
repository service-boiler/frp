<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\AuthorizationBrand;

class AuthorizationBrandRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return AuthorizationBrand::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [];
    }
}