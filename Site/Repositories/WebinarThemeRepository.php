<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\WebinarTheme\SortFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\WebinarThemeRequest;
use ServiceBoiler\Prf\Site\Models\WebinarTheme;

class WebinarThemeRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return WebinarTheme::class;
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