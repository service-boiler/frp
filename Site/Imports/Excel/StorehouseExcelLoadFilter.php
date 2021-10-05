<?php

namespace ServiceBoiler\Prf\Site\Imports\Url;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class StorehouseExcelLoadFilter implements IReadFilter
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