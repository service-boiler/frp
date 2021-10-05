<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\EquipmentCatalogSelectFilter;
use ServiceBoiler\Prf\Site\Filters\EquipmentSearchFilter;
use ServiceBoiler\Prf\Site\Models\Equipment;

class EquipmentRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Equipment::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [
            EquipmentSearchFilter::class,
            EquipmentCatalogSelectFilter::class,
        ];
    }
}