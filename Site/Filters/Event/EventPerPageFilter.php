<?php

namespace ServiceBoiler\Prf\Site\Filters\Event;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class EventPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.event', 10)];
    }
}