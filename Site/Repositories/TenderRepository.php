<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\Tender;

class TenderRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Tender::class;
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
