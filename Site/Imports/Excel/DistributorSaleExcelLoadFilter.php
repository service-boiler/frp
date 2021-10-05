<?php

namespace ServiceBoiler\Prf\Site\Imports\Excel;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class DistributorSaleExcelLoadFilter implements IReadFilter
{
    public function readCell($column, $row, $worksheetName = '')
    {

        if ($row >= 1 && $row <= config('site.storehouse_product_limit', 30000)) {
            if (in_array($column, range('A', 'B'))) {
                return true;
            }
        }
        return false;
    }

}