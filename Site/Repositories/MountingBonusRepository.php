<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\MountingBonus\MountingBonusProductFilter;
use ServiceBoiler\Prf\Site\Filters\MountingBonus\MountingBonusEquipmentFilter;
use ServiceBoiler\Prf\Site\Models\MountingBonus;

class MountingBonusRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return MountingBonus::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [
            MountingBonusEquipmentFilter::class,
            MountingBonusProductFilter::class
        ];
    }
}