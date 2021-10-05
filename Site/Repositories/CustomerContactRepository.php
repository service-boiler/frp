<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\CustomerContact;

class CustomerContactRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return CustomerContact::class;
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