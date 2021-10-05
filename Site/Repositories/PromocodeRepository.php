<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\Promocode\SortFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\PromocodeRequest;
use ServiceBoiler\Prf\Site\Models\Promocode;

class PromocodeRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Promocode::class;
    }

    /**
     * @return array
     */
    public function track():array
    {
        return [
            SortFilter::class
        ];
    }
}