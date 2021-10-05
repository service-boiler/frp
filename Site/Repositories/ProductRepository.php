<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\Product\LazyLoadFilter;
use ServiceBoiler\Prf\Site\Filters\Product\SearchFilter;
use ServiceBoiler\Prf\Site\Models\Product;

class ProductRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Product::class;
    }

    /**
     * @return array
     */
    public function track():array
    {
        return [
            LazyLoadFilter::class,
            SearchFilter::class,
//            ProductSortFilter::class,
        ];
    }
}