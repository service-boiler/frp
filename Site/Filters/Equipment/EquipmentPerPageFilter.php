<?php

namespace ServiceBoiler\Prf\Site\Filters\Equipment;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class EquipmentPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.equipment', 500)];
    }
}