<?php

namespace ServiceBoiler\Prf\Site\Filters\CurrencyArchive;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class CurrencyArchivePerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.archive', 25)];
    }
}