<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\ProductRetailSaleBonus\ProductRetailSaleBonusProductFilter;
use ServiceBoiler\Prf\Site\Filters\ProductRetailSaleBonus\ProductRetailSaleBonusEquipmentFilter;
use ServiceBoiler\Prf\Site\Models\ProductRetailSaleBonus;

class ProductRetailSaleBonusRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return ProductRetailSaleBonus::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [
            ProductRetailSaleBonusEquipmentFilter::class,
            ProductRetailSaleBonusProductFilter::class
        ];
    }
}