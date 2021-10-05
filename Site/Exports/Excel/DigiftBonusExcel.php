<?php

namespace ServiceBoiler\Prf\Site\Exports\Excel;

use ServiceBoiler\Prf\Site\Models\DigiftBonus;
use ServiceBoiler\Prf\Site\Models\DigiftExpense;
use ServiceBoiler\Prf\Site\Support\Excel;

class DigiftBonusExcel extends Excel
{
    /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet Sheet */
    private $_sheet;

    function build()
    {
        if (is_null($this->repository)) {
            echo "Невозможно создать xls файл, т.к. не указан репозиторий с данными";
            exit();
        }
        $this->getProperties()->setTitle(trans('site::digift_bonus.digift_bonuses'));
        $this->getActiveSheet()->setTitle(trans('site::digift_bonus.digift_bonuses'));
        $this->_sheet = $this->getActiveSheet();
        
		$count = 1;
        foreach (trans('site::digift_bonus.excel') as $cell => $value) {
            $this->_sheet->setCellValue($cell . $count, $value);
        }
		
		$this->_sheet->getColumnDimension('A')->setWidth(15);
        $this->_sheet->getColumnDimension('B')->setAutoSize(true);
        $this->_sheet->getColumnDimension('C')->setAutoSize(true);
        $this->_sheet->getColumnDimension('D')->setAutoSize(true);
        $this->_sheet->getColumnDimension('E')->setAutoSize(true);
        
        /**
         * @var int $key
         * @var Digift_bonus $digift_bonus
         */
		 
        foreach ($this->repository->all() as $digift_bonus) {
			$count++;
			if($digift_bonus->operationType=='decrease')$oVal=-$digift_bonus->operationValue;
			else $oVal=$digift_bonus->operationValue;
			if($digift_bonus->blocked==1)$blocked='бонус заблокирован';
			else $blocked='бонус начислен';
			
			$this->_sheet->setCellValue('A' . $count, $count - 1);
			$this->_sheet->setCellValue('B' . $count, $digift_bonus->created_at);
			$this->_sheet->setCellValue('C' . $count, $oVal);
			$this->_sheet->setCellValue('D' . $count, $digift_bonus->user_name);
			$this->_sheet->setCellValue('E' . $count, $blocked);
            
        }

    }

}