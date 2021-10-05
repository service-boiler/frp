<?php

namespace ServiceBoiler\Prf\Site\Exports\Excel;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use ServiceBoiler\Prf\Site\Models\Storehouse;
use ServiceBoiler\Prf\Site\Models\StorehouseProduct;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Support\Excel;

class StorehouseExcelIndex extends Excel
{
	/**
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 */
	function build()
	{
		//parent::build();

        if (is_null($this->repository) && is_null($this->model)) {
			echo "Невозможно создать xls файл, т.к. не указан репозиторий с данными или модель";
			exit();
		}

		$this->getProperties()->setTitle(trans('site::storehouse.header.quantity'));

        
        $this->_sheet2 = $this->getActiveSheet();
        $this->_sheet2->setTitle("Стоимость");
        
         
		$this->_sheet2->setCellValue('B1', 'Себестоимоть товаров на складах по ценам покупателя');

		foreach (trans('site::storehouse.excel.result') as $cell => $value) {
			$this->_sheet2->setCellValue($cell . '2', $value);
		}
       $this->_sheet2->getStyle('E' . '2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
       $this->_sheet2->getStyle('F' . '2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
       $this->_sheet2->getStyle('G' . '2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
       $this->_sheet2->getStyle('K' . '2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
       $this->_sheet2->getStyle('L' . '2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
       $this->_sheet2->getStyle('M' . '2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
       $this->_sheet2->getStyle('Q' . '2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
       $this->_sheet2->getStyle('R' . '2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
       $this->_sheet2->getStyle('S' . '2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
		foreach (trans('site::storehouse.excel.result_types') as $cell => $value) {
			$this->_sheet2->setCellValue($cell . '3', $value);
		}
        
        $count = 4;
        
        
        $today = Carbon::now()->format('Y-m-d');
        $periods=array();
        $periods['today']=Carbon::now()->format('Y-m-d');
        $periods['yesterday']=Carbon::now()->subDay()->format('Y-m-d');
        $periods['monday']=Carbon::now()->startOfWeek()->format('Y-m-d');
        $periods['first_day_month']=Carbon::now()->startOfMonth()->format('Y-m-d');
        $periods['first_day_year']=Carbon::now()->startOfYear()->format('Y-m-d');
        $periods['last_30']=Carbon::now()->subMonth()->format('Y-m-d');
        $periods['last_60']=Carbon::now()->subMonth(2)->format('Y-m-d');
        
        $ascs=User::whereHas('roles', function ($query){$query->whereIn('name', ['asc','csc']);})->where('active','1')->get();
        $users=User::whereHas('roles', function ($query){$query->whereIn('name', ['asc','csc','distr','gendistr']);})->where('active','1')->get();
        
        $storehouses=Storehouse::whereIn('user_id',$users->pluck('id'))->get();
        
        $this->_sheet2
            ->setCellValue('A' . $count,'Все склады')
            ->setCellValue('B' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_zip_summ',$periods['today']))
            ->setCellValue('C' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_equipment_summ',$periods['today']))
            ->setCellValue('D' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_accessories_summ',$periods['today']))
            
            ->setCellValue('E' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_zip_summ',$periods['yesterday']))
            ->getStyle('E' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
        $this->_sheet2    
            ->setCellValue('F' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_equipment_summ',$periods['yesterday']))
            ->getStyle('F' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
        $this->_sheet2    
            
            ->setCellValue('G' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_accessories_summ',$periods['yesterday']))
            ->getStyle('G' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
        $this->_sheet2    
            ->setCellValue('H' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_zip_summ',$periods['monday']))
            ->setCellValue('I' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_equipment_summ',$periods['monday']))
            ->setCellValue('J' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_accessories_summ',$periods['monday']));
        $this->_sheet2    
            ->setCellValue('K' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_zip_summ',$periods['first_day_month']))
            ->getStyle('K' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
        
        $this->_sheet2    
            ->setCellValue('L' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_equipment_summ',$periods['first_day_month']))
            ->getStyle('L' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
        $this->_sheet2    
            ->setCellValue('M' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_accessories_summ',$periods['first_day_month']))
            ->getStyle('M' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
        $this->_sheet2    
            ->setCellValue('N' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_zip_summ',$periods['last_30']))
            ->setCellValue('O' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_equipment_summ',$periods['last_30']))
            ->setCellValue('P' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_accessories_summ',$periods['last_30']));
        
        $this->_sheet2    
            ->setCellValue('Q' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_zip_summ',$periods['last_60']))
            ->getStyle('Q' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
        
        $this->_sheet2    
            ->setCellValue('R' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_equipment_summ',$periods['last_60']))
            ->getStyle('R' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
        
        $this->_sheet2    
            ->setCellValue('S' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_accessories_summ',$periods['last_60']))
            ->getStyle('S' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
        
        $this->_sheet2    
            ->setCellValue('T' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_zip_summ',$periods['first_day_year']))
            ->setCellValue('U' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_equipment_summ',$periods['first_day_year']))
            ->setCellValue('V' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_accessories_summ',$periods['first_day_year']));
        
        
        foreach (trans('site::storehouse.excel.result_types') as $cell => $value) {
			$this->_sheet2->getStyle($cell . $count)->getNumberFormat()->setFormatCode('\€\ # ##0');
		}
        $count++;
        
        foreach($storehouses as $storehouse) {
            $count++;
            //dd($storehouse->user->name);
            
            // $cells=array('A'.$count,'B'.$count,'C'.$count,'D'.$count,'E'.$count,'F'.$count,'G'.$count,'H'.$count,'I'.$count,'J'.$count,'K'.$count,'L'.$count,'M'.$count,'N'.$count,'O'.$count,'P'.$count,'Q'.$count,'R'.$count,'S'.$count,'T'.$count,'R'.$count,'S'.$count);
                // foreach($cells as $cell){
                    // $this->_sheet2->getStyle($cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('cccccc');
                // }
        
            $this->_sheet2->setCellValue('A' . $count, $storehouse->user->name);
            $this->_sheet2->setCellValue('B' . $count, $storehouse->cost_products_type_to_date('storehouse_zip_summ',$periods['today']));
            $this->_sheet2->setCellValue('C' . $count, $storehouse->cost_products_type_to_date('storehouse_equipment_summ',$periods['today']));
            $this->_sheet2->setCellValue('D' . $count, $storehouse->cost_products_type_to_date('storehouse_accessories_summ',$periods['today']));
            
            $this->_sheet2->setCellValue('E' . $count, $storehouse->cost_products_type_to_date('storehouse_zip_summ',$periods['yesterday']))
            ->getStyle('E' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
            $this->_sheet2->setCellValue('F' . $count, $storehouse->cost_products_type_to_date('storehouse_equipment_summ',$periods['yesterday']))
            ->getStyle('F' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
            $this->_sheet2->setCellValue('G' . $count, $storehouse->cost_products_type_to_date('storehouse_accessories_summ',$periods['yesterday']))
            ->getStyle('G' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
            
            $this->_sheet2->setCellValue('H' . $count, $storehouse->cost_products_type_to_date('storehouse_zip_summ',$periods['monday']));
            $this->_sheet2->setCellValue('I' . $count, $storehouse->cost_products_type_to_date('storehouse_equipment_summ',$periods['monday']));
            $this->_sheet2->setCellValue('J' . $count, $storehouse->cost_products_type_to_date('storehouse_accessories_summ',$periods['monday']));
            
            $this->_sheet2->setCellValue('K' . $count, $storehouse->cost_products_type_to_date('storehouse_zip_summ',$periods['first_day_month']))
            ->getStyle('K' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
            $this->_sheet2->setCellValue('L' . $count, $storehouse->cost_products_type_to_date('storehouse_equipment_summ',$periods['first_day_month']))
            ->getStyle('L' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
            $this->_sheet2->setCellValue('M' . $count, $storehouse->cost_products_type_to_date('storehouse_accessories_summ',$periods['first_day_month']))
            ->getStyle('M' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
            
            $this->_sheet2->setCellValue('N' . $count, $storehouse->cost_products_type_to_date('storehouse_zip_summ',$periods['last_30']));
            $this->_sheet2->setCellValue('O' . $count, $storehouse->cost_products_type_to_date('storehouse_equipment_summ',$periods['last_30']));
            $this->_sheet2->setCellValue('P' . $count, $storehouse->cost_products_type_to_date('storehouse_accessories_summ',$periods['last_30']));
            
            $this->_sheet2->setCellValue('Q' . $count, $storehouse->cost_products_type_to_date('storehouse_zip_summ',$periods['last_60']))
            ->getStyle('Q' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
            $this->_sheet2->setCellValue('R' . $count, $storehouse->cost_products_type_to_date('storehouse_equipment_summ',$periods['last_60']))
            ->getStyle('R' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
            $this->_sheet2->setCellValue('S' . $count, $storehouse->cost_products_type_to_date('storehouse_accessories_summ',$periods['last_60']))
            ->getStyle('S' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e9ffff');
            
            $this->_sheet2->setCellValue('T' . $count, $storehouse->cost_products_type_to_date('storehouse_zip_summ',$periods['first_day_year']));
            $this->_sheet2->setCellValue('U' . $count, $storehouse->cost_products_type_to_date('storehouse_equipment_summ',$periods['first_day_year']));
            $this->_sheet2->setCellValue('V' . $count, $storehouse->cost_products_type_to_date('storehouse_accessories_summ',$periods['first_day_year']));
            
            
            foreach (trans('site::storehouse.excel.result_types') as $cell => $value) {
                $this->_sheet2->getStyle($cell . $count)->getNumberFormat()->setFormatCode('\€\ # ##0');
            }
        }
        
        
        $this->_sheet2->getColumnDimension('A')->setWidth(45);
        
        
        $this->_sheet = $this->createSheet(1);
        $this->setActiveSheetIndex(1);
        $this->_sheet = $this->getActiveSheet();
		$this->_sheet->setTitle(trans('site::storehouse.header.quantity'));
        
        
        
		$count = 1;

		foreach (trans('site::storehouse.excel.index') as $cell => $value) {
			$this->_sheet->setCellValue($cell . $count, $value);
		}

		/**
		 * @var Storehouse $storehouse
		 */
		foreach ($this->repository->all() as $storehouse) {
			/**
			 * @var $storehouseProduct StorehouseProduct
			 */
			foreach ($storehouse->products as $storehouseProduct) {

				$count++;
				$this->_sheet
					->setCellValue('A' . $count, $count - 1)
					->setCellValue('B' . $count, $storehouse->user->name)
					->setCellValue('C' . $count, $storehouse->name)
					->setCellValue('D' . $count, $storehouse->user->region->name)
					->setCellValue('E' . $count, $storehouseProduct->product->sku)
					->setCellValue('F' . $count, $storehouseProduct->product->name)
					->setCellValue('G' . $count, $storehouseProduct->quantity)
					->setCellValue('I' . $count, $storehouseProduct->product->group->type->name)
					->setCellValue('J' . $count, $storehouseProduct->product->group->name);

				$this->_sheet
					->setCellValue('H' . $count, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($storehouse->getAttribute('uploaded_at')))
					->getStyle('H' . $count)
					->getNumberFormat()
					->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);

			}
		}

		$this->_sheet->getColumnDimension('A')->setWidth(5);
		$this->_sheet->getColumnDimension('B')->setWidth(25);
		$this->_sheet->getColumnDimension('C')->setWidth(15);
		$this->_sheet->getColumnDimension('D')->setWidth(20);
		$this->_sheet->getColumnDimension('E')->setAutoSize(true);
		$this->_sheet->getColumnDimension('F')->setWidth(35);
		$this->_sheet->getColumnDimension('G')->setAutoSize(true);
		$this->_sheet->getColumnDimension('H')->setWidth(25);
		$this->_sheet->getColumnDimension('I')->setWidth(15);
		$this->_sheet->getColumnDimension('J')->setWidth(28);
        
        $this->_sheet->setAutoFilter('A1:J1');
        
        

	}

}
