<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\ContragentSearchFilter;
use ServiceBoiler\Prf\Site\Models\Contragent;

class ContragentRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Contragent::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [
            ContragentSearchFilter::class,
        ];
    }
}