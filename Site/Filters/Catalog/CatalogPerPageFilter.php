<?php

namespace ServiceBoiler\Prf\Site\Filters\Catalog;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class CatalogPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.catalog', 25)];
    }
}