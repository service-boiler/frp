<?php

namespace ServiceBoiler\Prf\Site\Filters\Equipment;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;
use ServiceBoiler\Prf\Site\Models\Product;

class HasProductFilter extends Filter
{
    /**
     * @var null|Product
     */
    private $product;

    /**
     * @param $builder
     * @param RepositoryInterface $repository
     * @return mixed
     */
    function apply($builder, RepositoryInterface $repository)
    {
        if (!is_null($this->product)) {
            $builder = $builder->whereHas('products', function ($query) {
                $query->whereHas('details', function ($query) {
                    $query->whereId($this->product->id);
                });
                //whereEquipmentId($equipment_id);
            });
        } else {
            $builder = $builder->whereRaw("false");
        }

        return $builder;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }


}