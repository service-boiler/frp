<?php

namespace ServiceBoiler\Prf\Site\Filters\Tender;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class TenderPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.tender', 100)];
    }
}