<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\EsbCatalogService\SortFilter;
use ServiceBoiler\Prf\Site\Models\EsbCatalogServiceType;

class EsbCatalogServiceTypeRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return EsbCatalogServiceType::class;
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