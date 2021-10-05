<?php

namespace ServiceBoiler\Prf\Site\Filters\Authorization;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class AuthorizationPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.authorization', 250)];
    }
}