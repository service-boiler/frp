<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\ProductSpecRequest;
use ServiceBoiler\Prf\Site\Models\ProductSpec;

class ProductSpecRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return ProductSpec::class;
    }

    /**
     * @return array
     */
    public function track():array
    {
        return [

        ];
    }
}