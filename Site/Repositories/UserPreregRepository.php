<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\UserPrereg\PreregSearchFilter;
use ServiceBoiler\Prf\Site\Models\UserPrereg;

class UserPreregRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return UserPrereg::class;
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