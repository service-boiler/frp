<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\Organization\SortFilter;
use ServiceBoiler\Prf\Site\Models\Organization;

class OrganizationRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Organization::class;
    }

    /**
     * @return array
     */
    public function track():array
    {
        return [
            //SortFilter::class
        ];
    }
}