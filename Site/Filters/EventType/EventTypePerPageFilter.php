<?php

namespace ServiceBoiler\Prf\Site\Filters\EventType;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class EventTypePerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.event_type', 10)];
    }
}