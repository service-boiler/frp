<?php

namespace ServiceBoiler\Prf\Site\Filters\Ticket;

use ServiceBoiler\Repo\Filters\BootstrapSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\TicketStatus;

class TicketStatusFilter extends WhereFilter
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
        return ['' => trans('site::messages.select_no_matter')] + TicketStatus::orderBy('id')->pluck('name', 'id')->toArray();
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
        return 'Статус';
    }
    
    protected function attributes()
    {
        return parent::attributes()->merge(['style' => 'width:100px;']);
    }
}