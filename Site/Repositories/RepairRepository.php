<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\Repair\RepairIdSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\ActIncludeFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\ActSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\ClientSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\ContragentSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\RepairDateActFromFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\RepairDateActToFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\RepairDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\RepairDateToFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\RepairDateApprovedFromFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\RepairDateApprovedToFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\RepairCalledClientSelectFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\RepairHasSerialBoolFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\RepairIsFoundSerialFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\RepairPerPageFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\RegionFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\RepairUserFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\ScSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\EquipmentFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\PartSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\SearchSerialFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\SortFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\StatusFilter;
use ServiceBoiler\Prf\Site\Models\Repair;

class RepairRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Repair::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {   
        return [
            SortFilter::class,
            RepairIdSearchFilter::class,
            SearchSerialFilter::class,
            ClientSearchFilter::class,
            ScSearchFilter::class,
            ContragentSearchFilter::class,
            RepairUserFilter::class,
            RegionFilter::class,
            PartSearchFilter::class,
            EquipmentFilter::class,
            RepairIsFoundSerialFilter::class,
            RepairHasSerialBoolFilter::class,
            ActIncludeFilter::class,
            ActSearchFilter::class,
            RepairDateActFromFilter::class,
            RepairDateActToFilter::class,
            RepairDateFromFilter::class,
            RepairDateToFilter::class,
            RepairDateApprovedFromFilter::class,
            RepairDateApprovedToFilter::class,
            StatusFilter::class,
            RepairCalledClientSelectFilter::class,
            RepairPerPageFilter::class,

        ];
    }
}
