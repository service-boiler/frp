<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\UserPrice;

class UserPriceRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return UserPrice::class;
    }

    /**
     * @return array
     */
    public function track():array
    {
        return [

        ];
    }
}