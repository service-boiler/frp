<?php

namespace ServiceBoiler\Prf\Site\Filters\RetailSaleReport;

use ServiceBoiler\Repo\Filters\OrderByFilter;

class RetailSaleReportSortFilter extends OrderByFilter
{

    /**
     * @return array
     */
    public function defaults(): array
    {
        return [
            'retail_sale_reports.created_at' => 'DESC'
        ];
    }
}