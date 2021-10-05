<?php

namespace ServiceBoiler\Prf\Site\Filters\Webinar;

use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\Promocode;

class WebinarPromocodeFilter extends WhereFilter
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
        return ['' => trans('site::messages.select_no_matter')] + Promocode::query()
                ->whereEnabled('1')
                ->orderBy('name')->pluck('name', 'id')
                ->map(function ($item, $key) {
                    return str_limit($item, 125);
                })->toArray();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'promocode_id';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'promocode_id';

    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::admin.webinar.promocode');
    }

}