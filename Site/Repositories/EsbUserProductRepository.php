<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\EsbUserProduct;

class EsbUserProductRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return EsbUserProduct::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {   
        return [
           
        ];
    }
}
