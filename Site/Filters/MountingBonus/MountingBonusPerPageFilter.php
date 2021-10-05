<?php

namespace ServiceBoiler\Prf\Site\Filters\MountingBonus;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class MountingBonusPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.mounting_bonus', 10)];
    }
}