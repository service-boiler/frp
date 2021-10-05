<?php

namespace ServiceBoiler\Prf\Site\Filters\Announcement;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class AnnouncementPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.announcement', 10)];
    }
}