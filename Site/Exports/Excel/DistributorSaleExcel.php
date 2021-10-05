<?php

namespace ServiceBoiler\Prf\Site\Exports\Excel;

use ServiceBoiler\Prf\Site\Models\DistributorSale;
use ServiceBoiler\Prf\Site\Support\Excel;

class DistributorSaleExcel extends Excel
{
    /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet Sheet */
    private $_sheet;
    private $_sheet2;
	
	
	
	
    function build()
    {
        if (is_null($this->repository)) {
            echo "Невозможно создать xls файл, т.к. не указан репозиторий с данными";
            exit();
        }
        $this->_sheet = $this->getActiveSheet();
        $this->_sheet->setTitle("Days");
        foreach (trans('site::distributor_sales.excel') as $cell => $value) {
            $this->_sheet->setCellValue($cell, $value)->getStyle($cell)->getFont($cell)->setBold(true);
        }
    $count = 2;
	
        $this->_sheet->getColumnDimension('A')->setWidth(5);
        $this->_sheet->getColumnDimension('B')->setWidth(20);
        $this->_sheet->getColumnDimension('C')->setAutoSize(true);
        $this->_sheet->getColumnDimension('D')->setAutoSize(true);
        $this->_sheet->getColumnDimension('E')->setAutoSize(true);
        $this->_sheet->getColumnDimension('F')->setAutoSize(true);
        $this->_sheet->getColumnDimension('G')->setAutoSize(true);
        $this->_sheet->getColumnDimension('H')->setAutoSize(true);
        $this->_sheet->getColumnDimension('I')->setAutoSize(true);
        $this->_sheet->getStyle('C1')->getAlignment()->setHorizontal('center');
        $this->_sheet->getStyle('E1')->getAlignment()->setHorizontal('left');
       
        foreach ($this->repository->all() as $key => $sale) {
            $this->_buildDistributorSale($sale, $count, $this->_sheet);
            $count++;
            
        }
        $this->_sheet->setAutoFilter('A1:Z1');
        /*
        $this->_sheet2 = $this->createSheet(2);
       
        $this->_sheet2->setTitle("Monts");
        
        $this->_sheet2->getColumnDimension('A')->setWidth(5);
        $this->_sheet2->getColumnDimension('B')->setWidth(20);
        $this->_sheet2->getColumnDimension('D')->setAutoSize(true);
        $this->_sheet2->getColumnDimension('E')->setAutoSize(true);
        $this->_sheet2->getColumnDimension('F')->setAutoSize(true);
        $this->_sheet2->getColumnDimension('G')->setAutoSize(true);
        $this->_sheet2->getStyle('C1')->getAlignment()->setHorizontal('center');
        
        $count = 2;
        foreach (trans('site::distributor_sales.excel') as $cell => $value) {
            $this->_sheet2->setCellValue($cell, $value)->getStyle($cell)->getFont($cell)->setBold(true);
        }
        
        $sales = $this->repository->all()->groupBy('user_id');
        foreach($sales as $distr) {
        $this->_sheet2->setCellValue('B' . $count, $distr[0]->user->getAttribute('name'));
        $this->_sheet2->setCellValue('A' . $count, $count - 1)->getStyle('A' . $count)->getAlignment()->setHorizontal('left');
        $count++;
            for($i=0; $i<6; $i++) {
            $dateFrom=date("Y-m-d",strtotime("2020-"  .(date('m')-$i) ."-01"));
            $dateTo=date("Y-m-d",strtotime("2020-"  .(date('m')-$i+1) ."-01")-10);
            $salesDistrMonth=$distr->where('month',date("m", strtotime("-$i month")))->groupBy('product_id');
               $this->_sheet2->setCellValue('C' . $count, date("Y-m",strtotime("2020-"  .(date('m')-$i) ."-01")));
               $this->_sheet2->setCellValue('A' . $count, $count - 1)->getStyle('A' . $count)->getAlignment()->setHorizontal('left');
               $count++;
                foreach($salesDistrMonth as $saleProduct){
                $this->_sheet2->setCellValue('E' . $count, $saleProduct[0]->product->getAttribute('sku'));
                $this->_sheet2->getStyle('E' . $count)->getAlignment()->setHorizontal('center');
                $this->_sheet2->setCellValue('F' . $count, $saleProduct[0]->product->getAttribute('name'));
                $this->_sheet2->setCellValue('G' . $count, $saleProduct->sum('quantity'));
                 $this->_sheet2->setCellValue('I' . $count, date("Y-m",strtotime("2020-"  .(date('m')-$i) ."-01")));
                 $this->_sheet2->setCellValue('A' . $count, $count - 1)->getStyle('A' . $count)->getAlignment()->setHorizontal('left');
                $count++;
                }
            }
        
        //dd($distr->where('date_sale', '>','2020-07-01'));
        
        }
        */
        
        
    
    
    }

    private function _buildDistributorSale(DistributorSale $sale, $count, $sheet)
    {	$sheet->setCellValue('A' . $count, $count - 1)->getStyle('A' . $count)->getAlignment()->setHorizontal('left');
		
		$sheet->setCellValue('B' . $count, $sale->user->getAttribute('name'));
		
        $sheet->setCellValue('C' . $count, $sale->getAttribute('year') ." " .trans('site::messages.months.' .$sale->getAttribute('month'))) ;
        $sheet->setCellValue('D' . $count, $sale->getAttribute('week_of_month')) ;
        //$sheet->setCellValue('D' . $count, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($sale->getAttribute('month')) ."-" .$sale->getAttribute('week_of_month')) 
            //->getStyle('D' . $count)
            //->getNumberFormat()
            //->setFormatCode('dd.mm.yyyy');
            
            
         $sheet->getStyle('D' . $count)->getAlignment()->setHorizontal('center');
		$sheet->setCellValue('E' . $count, $sale->product->getAttribute('sku'));
		$sheet->setCellValue('F' . $count, $sale->product->getAttribute('name'));
		$sheet->setCellValue('G' . $count, $sale->getAttribute('quantity'));
		$sheet->setCellValue('H' . $count, $sale->product->group->type->name);
		$sheet->setCellValue('I' . $count, $sale->product->group->name);
		
        $this->_sheet->getStyle('E' . $count)->getAlignment()->setHorizontal('left');
        $this->_sheet->getStyle('G' . $count)->getAlignment()->setHorizontal('left');
        	
    }
    
    
    private function _buildDistributorSaleM(DistributorSale $sale, $count, $sheet)
    {	$sheet
			->setCellValue('A' . $count, $count - 1)->getStyle('A' . $count)->getAlignment()->setHorizontal('left');
		
		$sheet
			->setCellValue('B' . $count, $sale->product->getAttribute('sku'));
		
        	
    }

  
}
