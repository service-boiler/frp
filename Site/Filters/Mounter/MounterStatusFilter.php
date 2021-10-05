<?php

namespace ServiceBoiler\Prf\Site\Filters\Mounter;

use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\MounterStatus;

class MounterStatusFilter extends WhereFilter
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
        return ['' => trans('site::messages.select_no_matter')] +
            MounterStatus::query()
                ->has('mounters')
                ->orderBy('id')
                ->pluck('name', 'id')->toArray();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'status_id';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'status_id';

    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::mounter.status_id');
    }
}