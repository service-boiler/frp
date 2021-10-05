<?php

namespace ServiceBoiler\Prf\Site\Filters\Ticket;

use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\User;

class TicketCreatedByFilter extends WhereFilter
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
        return ['' => trans('site::messages.select_no_matter')] + User::query()->has('ticketsCreated')
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
        return 'created_by_id';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'created_by_id';

    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::ticket.created_by_id');
    }

}