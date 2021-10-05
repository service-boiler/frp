<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\RetailSaleReport\RetailSaleReportDateCreatedFromFilter;
use ServiceBoiler\Prf\Site\Filters\RetailSaleReport\RetailSaleReportDateCreatedToFilter;
use ServiceBoiler\Prf\Site\Filters\RetailSaleReport\RetailSaleReportEquipmentFilter;
use ServiceBoiler\Prf\Site\Filters\RetailSaleReport\RetailSaleReportProductFilter;
use ServiceBoiler\Prf\Site\Filters\RetailSaleReport\RetailSaleReportSortFilter;
use ServiceBoiler\Prf\Site\Filters\RetailSaleReport\RetailSaleReportStatusFilter;
use ServiceBoiler\Prf\Site\Filters\RetailSaleReport\SearchSerialFilter;
use ServiceBoiler\Prf\Site\Models\RetailSaleReport;

class RetailSaleReportRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return RetailSaleReport::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [
            RetailSaleReportSortFilter::class,
            SearchSerialFilter::class,
          //  RetailSaleReportStatusFilter::class,
          //  RetailSaleReportEquipmentFilter::class,
          //  RetailSaleReportProductFilter::class,
          //  RetailSaleReportDateCreatedFromFilter::class,
          //  RetailSaleReportDateCreatedToFilter::class,
            
        ];
    }
}
