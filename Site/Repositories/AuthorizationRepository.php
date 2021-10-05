<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\Authorization\AuthorizationStatusFilter;
use ServiceBoiler\Prf\Site\Filters\Authorization\AuthorizationTypeSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Authorization\AuthorizationDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\Authorization\AuthorizationDateToFilter;
use ServiceBoiler\Prf\Site\Models\Authorization;

class AuthorizationRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Authorization::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [
            AuthorizationDateFromFilter::class,
            AuthorizationDateToFilter::class,
            AuthorizationTypeSelectFilter::class,
            AuthorizationStatusFilter::class,
        ];
    }
}