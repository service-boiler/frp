<?php

namespace ServiceBoiler\Prf\Site\Filters\Event;

use ServiceBoiler\Repo\Filters\FerroliSingleSelect;
use ServiceBoiler\Repo\Filters\WhereFilter;
use ServiceBoiler\Prf\Site\Models\EventType;

class TypeSelectFilter extends WhereFilter
{

    use FerroliSingleSelect;

    protected $render = true;

    /**
     * Get the evaluated contents of the object.
     *
     * @return array
     */
    public function options(): array
    {
        $options = EventType::orderBy('sort_order')->where(config('site.check_field'), 1)->pluck('name', 'id');
        $options->prepend(trans('site::event.select_event_type'), '');

        return $options->toArray();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'type_id';
    }

    /**
     * @return string
     */
    public function column(): string
    {

        return 'events.type_id';

    }

    public function defaults(): array
    {
        return [''];
    }

    public function label()
    {
        return trans('site::event.type_id');
    }

}