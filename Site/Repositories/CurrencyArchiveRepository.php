<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\CurrencyArchive\CurrencyArchivePerPageFilter;
use ServiceBoiler\Prf\Site\Filters\CurrencyArchive\CurrencySelectFilter;
use ServiceBoiler\Prf\Site\Filters\CurrencyArchive\SortFilter;
use ServiceBoiler\Prf\Site\Filters\Mounting\CurrencyArchiveDateFilter;
use ServiceBoiler\Prf\Site\Models\CurrencyArchive;

class CurrencyArchiveRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return CurrencyArchive::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [
            SortFilter::class,
            CurrencySelectFilter::class,
            CurrencyArchiveDateFilter::class,
            CurrencyArchivePerPageFilter::class
        ];
    }
}