<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\Mounting\MountingActIncludeFilter;
use ServiceBoiler\Prf\Site\Filters\Mounting\MountingClientSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Mounting\MountingDateCreatedFromFilter;
use ServiceBoiler\Prf\Site\Filters\Mounting\MountingDateCreatedToFilter;
use ServiceBoiler\Prf\Site\Filters\Mounting\OrderCreatedAtFromFilter;
use ServiceBoiler\Prf\Site\Filters\Mounting\OrderCreatedAtToFilter;
use ServiceBoiler\Prf\Site\Filters\Mounting\MountingEquipmentFilter;
use ServiceBoiler\Prf\Site\Filters\Mounting\MountingProductFilter;
use ServiceBoiler\Prf\Site\Filters\Mounting\MountingSortFilter;
use ServiceBoiler\Prf\Site\Filters\Mounting\MountingDateMountingFromFilter;
use ServiceBoiler\Prf\Site\Filters\Mounting\MountingDateMountingToFilter;
use ServiceBoiler\Prf\Site\Filters\Mounting\MountingStatusFilter;
use ServiceBoiler\Prf\Site\Filters\Mounting\MountingSerialFilter;
use ServiceBoiler\Prf\Site\Models\Mounting;

class MountingRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Mounting::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [
            MountingSortFilter::class,
            MountingClientSearchFilter::class,
            MountingSerialFilter::class,
            MountingStatusFilter::class,
            MountingEquipmentFilter::class,
            MountingProductFilter::class,
            MountingDateCreatedFromFilter::class,
            MountingDateCreatedToFilter::class,
            MountingDateMountingFromFilter::class,
            MountingDateMountingToFilter::class,
            MountingActIncludeFilter::class,
        ];
    }
}
