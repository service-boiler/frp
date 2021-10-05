<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\Act\ActContragentFilter;
use ServiceBoiler\Prf\Site\Filters\Act\ActUserFilter;
use ServiceBoiler\Prf\Site\Filters\Act\ActDateCreatedFromFilter;
use ServiceBoiler\Prf\Site\Filters\Act\ActDateCreatedToFilter;
use ServiceBoiler\Prf\Site\Filters\Act\ActSortFilter;
use ServiceBoiler\Prf\Site\Filters\Act\ActTypeFilter;
use ServiceBoiler\Prf\Site\Filters\Act\ActBoolPaidFilter;
use ServiceBoiler\Prf\Site\Filters\Act\ActBoolReceivedFilter;
use ServiceBoiler\Prf\Site\Filters\Act\ContragentSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Act\ScSearchFilter;
use ServiceBoiler\Prf\Site\Models\Act;

class ActRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Act::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [
            ActSortFilter::class,
            ScSearchFilter::class,
            ActUserFilter::class,
            ContragentSearchFilter::class,
            ActContragentFilter::class,
            ActBoolReceivedFilter::class,
            ActBoolPaidFilter::class,
            ActDateCreatedFromFilter::class,
            ActDateCreatedToFilter::class,
            ActTypeFilter::class,
        ];
    }
}