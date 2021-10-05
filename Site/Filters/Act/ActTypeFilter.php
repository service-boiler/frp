<?php

namespace ServiceBoiler\Prf\Site\Filters\Act;

use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\ActType;

class ActTypeFilter extends WhereFilter
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
        return ['' => trans('site::messages.select_no_matter')] + ActType::query()
                ->has('acts')
                ->orderBy('id')
                ->pluck('name', 'id')->toArray();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'type';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'acts.type_id';

    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::act.type_id');
    }
}