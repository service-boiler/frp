<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\CountrySortFilter;
use ServiceBoiler\Prf\Site\Models\Country;

class CountryRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Country::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [
            CountrySortFilter::class
        ];
    }

}