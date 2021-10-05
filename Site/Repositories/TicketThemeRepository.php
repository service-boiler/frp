<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\TicketTheme\SortFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\TicketThemeRequest;
use ServiceBoiler\Prf\Site\Models\TicketTheme;

class TicketThemeRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return TicketTheme::class;
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