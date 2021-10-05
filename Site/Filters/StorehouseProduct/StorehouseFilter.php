<?php

namespace ServiceBoiler\Prf\Site\Filters\StorehouseProduct;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class StorehouseFilter extends Filter
{
    /**
     * @var string|null
     */
    private $storehouse_id;

    function apply($builder, RepositoryInterface $repository)
    {
        if (!is_null($this->storehouse_id)) {
            $builder = $builder->where('storehouse_id', $this->storehouse_id);
        }

        return $builder;
    }

    /**
     * @param null $storehouse_id
     * @return $this
     */
    public function setStorehouseId($storehouse_id = null)
    {
        $this->storehouse_id = $storehouse_id;

        return $this;
    }
}