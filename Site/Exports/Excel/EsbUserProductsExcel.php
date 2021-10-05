<?php

namespace ServiceBoiler\Prf\Site\Exports\Excel;

use ServiceBoiler\Prf\Site\Models\EsbUserProduct;
use ServiceBoiler\Prf\Site\Support\Excel;

class EsbUserProductsExcel extends Excel
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
        foreach (trans('site::user.esb_user_product_excel') as $cell => $value) {
            $this->_sheet->setCellValue($cell .'1', $value)->getStyle($cell)->getFont($cell)->setBold(true);
        }
    $count = 2;
    $column = 1;
	$second = 0;
    $this->_sheet->getStyle('A:Z')->getAlignment()->setVertical('center');
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(5);
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(45);
                    
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(33);
                    
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
        
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
        
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
       
        foreach ($this->repository->all() as $key => $esbUserProduct) {
            $this->_buildEsbUserProduct($esbUserProduct, $count);
            $count++;
            
        }

    }

    private function _buildEsbUserProduct(EsbUserProduct $esbUserProduct, $count)
    {	
        $column=1;
        //$this->_sheet->getRowDimension($count)->setRowHeight(80);
        $this->_sheet
			->setCellValue(trans('site::excel.col.'.$column) . $count, $count - 1)->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('left');
		
        $column++;
		$this->_sheet
			->setCellValue(trans('site::excel.col.'.$column) . $count, $esbUserProduct->product ? $esbUserProduct->product->name : $esbUserProduct->product_no_cat);
		
        $column++; 
		$this->_sheet
			->setCellValue(trans('site::excel.col.'.$column) . $count, $esbUserProduct->serial);
		
        $column++;  
		$this->_sheet
			->setCellValue(trans('site::excel.col.'.$column) . $count, $esbUserProduct->address_filtred);
		
        $column++;
		$this->_sheet
			->setCellValue(trans('site::excel.col.'.$column) . $count, $esbUserProduct->user_filtred);
		
        $column++;
		$this->_sheet
			->setCellValue(trans('site::excel.col.'.$column) . $count, $esbUserProduct->phone_filtred);
		
        $column++;        
		$this->_sheet
            ->setCellValue(trans('site::excel.col.'.$column) . $count, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($esbUserProduct->launch() ? $esbUserProduct->launch()->date_launch: ''))
            ->getStyle(trans('site::excel.col.'.$column) . $count)
            ->getNumberFormat()
            ->setFormatCode('dd.mm.yyyy');
			$this->_sheet->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('center');
		
        $column++;  
        if($esbUserProduct->maintenances()->exists())
        {    
		$this->_sheet
            ->setCellValue(trans('site::excel.col.'.$column) . $count, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($esbUserProduct->maintenances()->first()->date))
            ->getStyle(trans('site::excel.col.'.$column) . $count)
            ->getNumberFormat()
            ->setFormatCode('dd.mm.yyyy');
			$this->_sheet->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('center');
		}
        $column++;
        if($esbUserProduct->visits()->exists())
        {
		$this->_sheet
            ->setCellValue(trans('site::excel.col.'.$column) . $count, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($esbUserProduct->visits()->first()->date_planned))
            ->getStyle(trans('site::excel.col.'.$column) . $count)
            ->getNumberFormat()
            ->setFormatCode('dd.mm.yyyy');
			$this->_sheet->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('center');
		}
        
        
        $column++;  
        if($esbUserProduct->next_maintenance)
        {        
		$this->_sheet
            ->setCellValue(trans('site::excel.col.'.$column) . $count, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($esbUserProduct->next_maintenance))
            ->getStyle(trans('site::excel.col.'.$column) . $count)
            ->getNumberFormat()
            ->setFormatCode('dd.mm.yyyy');
			$this->_sheet->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('center');
		
		}
    }
}
