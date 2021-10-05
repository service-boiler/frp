<?php

namespace ServiceBoiler\Prf\Site\Filters\Contact;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class ContactPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.contact', 10)];
    }
}