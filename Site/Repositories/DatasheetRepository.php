<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\Datasheet\DatasheetSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Datasheet\DatasheetSortFilter;
use ServiceBoiler\Prf\Site\Filters\Datasheet\DatasheetTypeSelectFilter;
use ServiceBoiler\Prf\Site\Models\Datasheet;

class DatasheetRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Datasheet::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [
            DatasheetSortFilter::class,
            DatasheetSearchFilter::class,
            DatasheetTypeSelectFilter::class,
        ];
    }
}