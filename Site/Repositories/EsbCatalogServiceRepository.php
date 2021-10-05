<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\EsbCatalogService\SortFilter;
use ServiceBoiler\Prf\Site\Models\EsbCatalogService;

class EsbCatalogServiceRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return EsbCatalogService::class;
    }

    /**
     * @return array
     */
    public function track():array
    {
        return [
 //           SortFilter::class
        ];
    }
}