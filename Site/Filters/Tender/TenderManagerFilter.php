<?php

namespace ServiceBoiler\Prf\Site\Filters\Tender;

use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\User;

class TenderManagerFilter extends WhereFilter
{
    use BootstrapSelect;

    protected $render = true;

    /**
     * Get the evaluated contents of the object.
     *
     * @return array
     */
    public function options(): array
    {
        return ['' => trans('site::messages.select_no_matter')] + User::query()->has('tenders')
                ->orderBy('name')->pluck('name', 'id')
                ->map(function ($item, $key) {
                    return str_limit($item, config('site.name_limit', 25));
                })->toArray();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'manager_id';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'manager_id';

    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::tender.manager_id');
    }

}