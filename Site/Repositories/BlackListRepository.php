<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\BlackList;

class BlackListRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return BlackList::class;
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