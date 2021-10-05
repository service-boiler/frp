<?php

namespace ServiceBoiler\Prf\Site\Filters\Event;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;
use ServiceBoiler\Prf\Site\Models\EventType;

class EventTypeFilter extends Filter
{

    /**
     * @var null|EventType
     */
    private $event_type;

    function apply($builder, RepositoryInterface $repository)
    {
        return $builder->where("type_id", $this->event_type->getAttribute('id'));
    }

    /**
     * @param EventType $event_type
     * @return $this
     */
    public function setEventType(EventType $event_type)
    {
        $this->event_type = $event_type;

        return $this;
    }
}
