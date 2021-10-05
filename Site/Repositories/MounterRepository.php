<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\Mounter\MounterSortFilter;
use ServiceBoiler\Prf\Site\Models\Mounter;

class MounterRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Mounter::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [
            MounterSortFilter::class,
//            MountingDateCreatedFromFilter::class,
//            MountingDateCreatedToFilter::class,
        ];
    }
}