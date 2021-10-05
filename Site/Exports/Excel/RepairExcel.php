<?php

namespace ServiceBoiler\Prf\Site\Exports\Excel;

use ServiceBoiler\Prf\Site\Models\Repair;
use ServiceBoiler\Prf\Site\Support\Excel;

class RepairExcel extends Excel
{
    /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet Sheet */
    private $_sheet;
	
	
	
	
    function build()
    {
        if (is_null($this->repository)) {
            echo "Невозможно создать xls файл, т.к. не указан репозиторий с данными";
            exit();
        }
        $this->_sheet = $this->getActiveSheet();
        foreach (trans('site::repair.excel') as $cell => $value) {
            $this->_sheet->setCellValue($cell, $value)->getStyle($cell)->getFont($cell)->setBold(true);
        }
    $count = 2;
	$second = 0;
        $this->_sheet->getColumnDimension('A')->setWidth(5);
        $this->_sheet->getColumnDimension('B')->setWidth(8);
        $this->_sheet->getColumnDimension('C')->setWidth(20);
        $this->_sheet->getColumnDimension('D')->setAutoSize(true);
        $this->_sheet->getColumnDimension('E')->setAutoSize(true);
        $this->_sheet->getColumnDimension('F')->setAutoSize(true);
        $this->_sheet->getColumnDimension('G')->setAutoSize(true);
        $this->_sheet->getColumnDimension('H')->setWidth(30);
        $this->_sheet->getColumnDimension('I')->setWidth(30);
        $this->_sheet->getColumnDimension('J')->setWidth(15);
        $this->_sheet->getColumnDimension('K')->setAutoSize(true);
        $this->_sheet->getColumnDimension('L')->setAutoSize(true);
        $this->_sheet->getColumnDimension('M')->setAutoSize(true);
        $this->_sheet->getColumnDimension('N')->setAutoSize(true);
        $this->_sheet->getColumnDimension('O')->setAutoSize(true);
        $this->_sheet->getColumnDimension('P')->setAutoSize(true);
        $this->_sheet->getColumnDimension('Q')->setAutoSize(true);
        $this->_sheet->getColumnDimension('R')->setAutoSize(true);
        $this->_sheet->getColumnDimension('S')->setAutoSize(true);
        $this->_sheet->getColumnDimension('T')->setAutoSize(true);
        $this->_sheet->getColumnDimension('U')->setAutoSize(true);
        $this->_sheet->getColumnDimension('V')->setAutoSize(true);
        $this->_sheet->getColumnDimension('W')->setAutoSize(true);
        $this->_sheet->getColumnDimension('X')->setAutoSize(true);
        $this->_sheet->getColumnDimension('Y')->setAutoSize(true);
        $this->_sheet->getColumnDimension('Z')->setAutoSize(true);
        $this->_sheet->getColumnDimension('AA')->setAutoSize(true);
        $this->_sheet->getColumnDimension('AB')->setAutoSize(true);
        $this->_sheet->getColumnDimension('AC')->setAutoSize(true);
        $this->_sheet->getColumnDimension('AD')->setAutoSize(true);
        $this->_sheet->getColumnDimension('AE')->setWidth(10);
        $this->_sheet->getColumnDimension('AF')->setWidth(10);
        $this->_sheet->getColumnDimension('AG')->setWidth(30);
        $this->_sheet->getColumnDimension('AH')->setWidth(30);
        $this->_sheet->getColumnDimension('AI')->setWidth(30);
        foreach ($this->repository->all() as $key => $repair) {
            $this->_buildRepair($repair, $count);
           
            if (($parts = $repair->parts())->exists()) {
				$second = 0;
                foreach ($parts->get() as $k => $part) {
                    if ($k > 0) {
                        $count++;
                       if ($second = 0) { $this->_buildRepair($repair, $count); }	
                    }
            $this->_sheet->setCellValue('A' . $count, $count - 1)->getStyle('A' . $count)->getAlignment()->setHorizontal('left');
			if ($repair->act()->exists()) {
            $this->_sheet->setCellValue('C' . $count, $repair->act->getAttribute('number'));
            }
			
			$this->_sheet
				->setCellValue('K' . $count, $part->product->getAttribute('name'))
				->setCellValue('L' . $count, $part->product->getAttribute('RepairPrice')->price *  $part->getAttribute('count'))
                ->setCellValue('M' . $count, $part->product->getAttribute('sku'))
				->setCellValue('AD' . $count, $part->cost() *  $part->getAttribute('count'));
				$second++;
                }
            }
            $count++;
            
        }

    }

    private function _buildRepair(Repair $repair, $count)
    {	$this->_sheet
			->setCellValue('A' . $count, $count - 1)->getStyle('A' . $count)->getAlignment()->setHorizontal('left');
		if ($repair->act()->exists()) {
		$this->_sheet
			->setCellValue('B' . $count, 'есть ' .$repair->id);
		} else {
		$this->_sheet
			->setCellValue('B' . $count, 'нет');
		}
		if ($repair->act()->exists()) {
		$this->_sheet
			->setCellValue('C' . $count, $repair->act->getAttribute('number'));
		}
        $this->_sheet
			->setCellValue('D' . $count, $repair->product->getAttribute('name'))
            ->setCellValue('E' . $count, $repair->getAttribute('serial_id'));
			
		$this->_sheet
            ->setCellValue('F' . $count, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($repair->getAttribute('date_trade')))
            ->getStyle('F' . $count)
            ->getNumberFormat()
            ->setFormatCode('dd.mm.yyyy');
			$this->_sheet->getStyle('F' . $count)->getAlignment()->setHorizontal('center');
		$this->_sheet
            ->setCellValue('G' . $count, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($repair->getAttribute('date_launch')))
            ->getStyle('G' . $count)
            ->getNumberFormat()
            ->setFormatCode('dd.mm.yyyy');
			$this->_sheet->getStyle('G' . $count)->getAlignment()->setHorizontal('center');
		$this->_sheet
			->setCellValue('H' . $count, $repair->getAttribute('address'));
		
		$this->_sheet
			->setCellValue('I' . $count, $repair->getAttribute('client'))
			->setCellValue('J' . $count, "'" . $repair->country->getAttribute('phone') . $repair->getAttribute('phone_primary'));
		
		$this->_sheet	
            ->setCellValue('N' . $count, $repair->cost_difficulty())
			->setCellValue('O' . $count, $repair->cost_distance());
			
		$this->_sheet
            ->setCellValue('P' . $count, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($repair->getAttribute('date_repair')))
            ->getStyle('P' . $count)
            ->getNumberFormat()
            ->setFormatCode('dd.mm.yyyy');
		$this->_sheet	
			->setCellValue('Q' . $count, $repair->getAttribute('TotalCost'));
		
			
        if ($repair->contragent()->exists()) {
            $this->_sheet->setCellValue('R' . $count, $repair->contragent->getAttribute('name'));
            if (($addresses = $repair->contragent->addresses()->where('type_id', 1))->exists()) {
                $this->_sheet->setCellValue('S' . $count, $addresses->first()->getAttribute('locality'));
            }
        }
		$this->_sheet->setCellValue('T' . $count, $repair->user->region->name);
		$this->_sheet->setCellValue('U' . $count, $repair->user->region->italy_district->name);
		$this->_sheet
			->setCellValue('V' . $count, $repair->getAttribute('id'));
        $this->_sheet
            ->setCellValue('W' . $count, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($repair->getAttribute('created_at')))
            ->getStyle('W' . $count)
            ->getNumberFormat()
            ->setFormatCode('dd.mm.yyyy');
		$this->_sheet
			->setCellValue('X' . $count, $repair->status->getAttribute('name'));
       if ($repair->getAttribute('approved_at')) {
	   $this->_sheet->setCellValue('Y' . $count, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($repair->getAttribute('approved_at')))
			->getStyle('Y' . $count)
            ->getNumberFormat()
            ->setFormatCode('dd.mm.yyyy');
	   }
	   
	   if ($repair->act()->exists()) {
            if ($repair->act->getAttribute('received')) 
				$this->_sheet->setCellValue('Z' . $count, "ПОЛУЧЕН");
			else $this->_sheet->setCellValue('Z' . $count, "НЕТ");
        }
	   
	   if ($repair->act()->exists()) {
            if ($repair->act->getAttribute('paid')) 
				$this->_sheet->setCellValue('AA' . $count, "ОПЛАЧЕН");
			else $this->_sheet->setCellValue('AA' . $count, "НЕТ");
        }
		
		$this->_sheet
			->setCellValue('AB' . $count, $repair->product->getAttribute('sku'))
            ->setCellValue('AC' . $count, $repair->distance->getAttribute('name'))
			
			
			->setCellValue('AH' . $count, $repair->getAttribute('reason_call'))
            ->setCellValue('AH' . $count, $repair->getAttribute('diagnostics'))
            ->setCellValue('AI' . $count, $repair->getAttribute('works'));
			
    }
}
