<?php

namespace ServiceBoiler\Prf\Site\Support;

class SerialLoadFilter implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter
{
    public function readCell($column, $row, $worksheetName = '') {
        
        if ($row >= 1 && $row <= 60000) {
            if (in_array($column,range('A','C'))) {
                return true;
            }
        }
        return false;
    }

}