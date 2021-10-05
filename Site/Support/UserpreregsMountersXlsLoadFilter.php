<?php

namespace ServiceBoiler\Prf\Site\Support;

class UserpreregsMountersXlsLoadFilter implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter
{
    public function readCell($column, $row, $worksheetName = '') {
        
        if ($row >= 1 && $row <= 60000) {
            if (in_array($column,range('A','G'))) {
                return true;
            }
        }
        return false;
    }

}