<?php

namespace ServiceBoiler\Prf\Site\Filters\Message;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class MessagePerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.message', 25)];
    }
}