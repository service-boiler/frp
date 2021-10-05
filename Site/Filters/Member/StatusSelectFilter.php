<?php

namespace ServiceBoiler\Prf\Site\Filters\Member;

use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\MemberStatus;

class StatusSelectFilter extends WhereFilter
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
        $options = MemberStatus::orderBy('sort_order')->pluck('name', 'id');
        $options->prepend(trans('site::messages.select_from_list'), '');

        return $options->toArray();
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

        return 'members.status_id';

    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::member.status_id');
    }

}