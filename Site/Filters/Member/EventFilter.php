<?php

namespace ServiceBoiler\Prf\Site\Filters\Member;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;
use ServiceBoiler\Prf\Site\Models\Event;

class EventFilter extends Filter
{
    /**
     * @var Event
     */
    private $event;

    function apply($builder, RepositoryInterface $repository)
    {
        if (!is_null($this->event)) {
            $builder = $builder->whereEventId($this->event->id);
        }


        return $builder;
    }


    /**
     * @param Event $event
     * @return $this
     */
    public function setEvent(Event $event = null)
    {
        $this->event = $event;

        return $this;
    }
}