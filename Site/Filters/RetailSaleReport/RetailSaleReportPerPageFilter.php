<?php

namespace ServiceBoiler\Prf\Site\Filters\RetailSaleReport;

use ServiceBoiler\Repo\Filters\PerPageFilter;

class RetailSaleReportPerPageFilter extends PerPageFilter
{
    public function defaults(): array
    {
        return [config('site.per_page.retail_sale_report', 100)];
    }
}