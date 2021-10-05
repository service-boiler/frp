<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\RevisionPart\RevisionPartDateChangeFromFilter;
use ServiceBoiler\Prf\Site\Models\RevisionPart;

class RevisionPartRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return RevisionPart::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {   
        return [
           
           //RevisionPartSearchFilter::class,
        ];
    }
}
