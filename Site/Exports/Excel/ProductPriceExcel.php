<?php

namespace ServiceBoiler\Prf\Site\Exports\Excel;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Support\Excel;
use ServiceBoiler\Prf\Site\Models\Variable;

class ProductPriceExcel extends Excel
{
    /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet Sheet */
    private $_sheet;
    
    function build()
    {
       if (is_null($this->repository)) {
            echo "Невозможно создать xls файл, т.к. не указан репозиторий с данными";
            exit();
        }
        $static_currency = Variable::whereName('currency_static_euro')->first();	
        
        $this->getProperties()->setTitle('Price Ferroli');
        $this->getActiveSheet()->setTitle('service.ferroli.ru_' .Carbon::now()->format('Y-m-d'));
        $this->_sheet = $this->getActiveSheet();
      
        $count = 1;
        $lcount = 1;
        
        $this->_sheet->getColumnDimension('A')->setWidth(23);
        $this->_sheet->getColumnDimension('B')->setWidth(28);
        $this->_sheet->getColumnDimension('C')->setWidth(12);
        $this->_sheet->getColumnDimension('D')->setWidth(12);
        $this->_sheet->getColumnDimension('E')->setWidth(19);
        $this->_sheet->getColumnDimension('F')->setWidth(24);
        $this->_sheet->getColumnDimension('G')->setWidth(8);
        $this->_sheet->getColumnDimension('H')->setWidth(32);
        
        $this->_sheet
            ->setCellValue('A' . $count, 'Артикул')
            ->setCellValue('B' . $count, 'Наименование')
            ->setCellValue('C' . $count, 'Цена РРЦ ₽')
            ->setCellValue('D' . $count, 'Цена РРЦ €')
            ->setCellValue('E' . $count, 'Ваша цена на сайте')
            ->setCellValue('H' . $count, 'Дата формирования прайс-листа: ')
            ->setCellValue('I' . $count, Carbon::now()->format('Y-m-d'));
            
        if(!empty($static_currency)) {
                $this->_sheet
                    ->setCellValue('F' . $count, 'Внутренний курс Евро: ')
                    ->setCellValue('G' . $count, $static_currency->value);
            }
            
        $this->_sheet->getStyle('G' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('fe7902');
        $this->_sheet->getStyle('G' . $count)->getAlignment()->setHorizontal('left');
        
        $head=array('A1','B1','C1','D1','E1','G1');
        foreach($head as $cell){
            $this->_sheet->getStyle($cell)->getFont()->setBold(true);
            $this->_sheet->getStyle($cell)->getAlignment()->setWrapText(true);
        }
        
        foreach ($this->repository->all() as $key => $product) {
			$count++;
            $this->_buildProduct($product, $count);
            
        }
            
    }
    
    private function _buildProduct(Product $product, $count)
    {
		$this->_sheet
            ->setCellValue('A' . $count, $product->getAttribute('sku'))
            ->setCellValue('B' . $count, $product->name)
			->setCellValue('E' . $count, $product->price->value);
		if(!empty($product->retail_price_rub->value)) {
            $this->_sheet->setCellValue('C' . $count, $product->retail_price_rub->value);
            }
        if(!empty($product->retail_price_eur->value_raw)) {
            $this->_sheet
			->setCellValue('D' . $count, $product->retail_price_eur->value_raw);
            }
            
            
            if($product->oldprice<>0 && $product->oldprice !=$product->price->value){
                
                $this->_sheet->setCellValue('F' . $count, 'распродажа. Старая цена: ');
                $this->_sheet->getStyle('F' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('eae700');
                $this->_sheet->getStyle('G' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('eae700');
                $this->_sheet->getStyle('G' . $count)->getNumberFormat()->setFormatCode('\₽\ # ##0');
                $this->_sheet->setCellValue('G' . $count, $product->oldprice);
                $this->_sheet->getStyle('G' . $count)->getAlignment()->setHorizontal('left');
            
            }
            
            
            $this->_sheet->getStyle('A' . $count)->getAlignment()->setHorizontal('left');
            $this->_sheet->getStyle('B' . $count)->getAlignment()->setHorizontal('left');
            $this->_sheet->getStyle('C' . $count)->getNumberFormat()->setFormatCode('\₽\ # ##0');
            $this->_sheet->getStyle('D' . $count)->getNumberFormat()->setFormatCode('\€\ # ##0');
            $this->_sheet->getStyle('E' . $count)->getNumberFormat()->setFormatCode('\₽\ # ##0');
    }
 
}
