<?php

namespace ServiceBoiler\Prf\Site\Filters\Mission;

use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\User;

class MissionCreatedByFilter extends WhereFilter
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
        return ['' => trans('site::messages.select_no_matter')] + User::query()->has('missionsCreated')
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
        return 'created_by_user_id';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'created_by_user_id';

    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::admin.mission.created_by');
    }

}