<?php

namespace ServiceBoiler\Prf\Site\Filters\Member;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class MemberPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.member', 10)];
    }
}