<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\Bank\SearchFilter;
use ServiceBoiler\Prf\Site\Models\Bank;

class BankRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Bank::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [
            SearchFilter::class
        ];
    }
}