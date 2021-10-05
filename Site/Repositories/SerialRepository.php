<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\Serial\ProductSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Serial\SearchFilter;
use ServiceBoiler\Prf\Site\Models\Serial;

class SerialRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Serial::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [
            SearchFilter::class,
            ProductSearchFilter::class
        ];
    }
}