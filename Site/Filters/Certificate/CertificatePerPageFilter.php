<?php

namespace ServiceBoiler\Prf\Site\Filters\Certificate;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class CertificatePerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.certificate', 10)];
    }
}