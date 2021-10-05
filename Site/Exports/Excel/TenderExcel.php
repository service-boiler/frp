<?php

namespace ServiceBoiler\Prf\Site\Exports\Excel;

use Carbon\Carbon;
use ServiceBoiler\Prf\Site\Models\Tender;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Support\Excel;

class TenderExcel extends Excel
{
    /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet Sheet */
    private $_sheet;
	
	
	
	
    function build()
    {
        if (is_null($this->repository)) {
            echo "Невозможно создать xls файл, т.к. не указан репозиторий с данными";
            exit();
        }
        $i=0;    
        $sheet = $this->getActiveSheet();
        $sheet->setTitle('tenders');
        $this->_buildSheet ($sheet, $i);
        
    }

    
    private function _buildSheet ($sheet, $i=0) {
        $sheet->getStyle('A1:P1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('b6f2a0');
        $sheet->freezePane('A2');;
        $sheet->getStyle('A1:P1')->getAlignment()->setVertical('center');
       
       $header=['1'=>'Менеджер, ' .chr(10).'№ заявки','2'=>'Дата создания',
        '3'=>'Город',
        '4'=>'Дистрибьютор',
        '5'=>'Месяц закупки',
        '6'=>'Цена до',
        '7'=>'Курс',
        '8'=>'Оборудование, кол-во, цена',
        '9'=>'Статус',
        '10'=>'Конкурентные предложения',
        '11'=>'Комментарии',
        '12'=>'Результат',
        '13'=>'Адрес',
        '14'=>'Название объекта',
        '15'=>'Тип объекта',
        
        ];
        
        foreach ($header as $cell => $value) {
            $sheet->setCellValue(trans('site::excel.col.'.$cell).'1', $value)->getStyle(trans('site::excel.col.'.$cell).'1')
            ->getFont(trans('site::excel.col.'.$cell).'1')->setBold(true);
            $sheet->getStyle(trans('site::excel.col.'.$cell).'1')
            ->getAlignment()->setWrapText(true);
            
        }
        $row_num = 2;
        $column = 1;
        $second = 0;
        
        
        $sheet->getStyle('A:Z')->getAlignment()->setVertical('center');
        
        //Ширина столбцов
        $cols_width=[
            '1'=>'12','2'=>'12','3'=>'20','4'=>'25','5'=>'12','6'=>'0','7'=>'0',
            '8'=>'35','9'=>'25','10'=>'30','11'=>'0','12'=>'0','13'=>'0','14'=>'0',
            '15'=>'0',
            ];
        
        foreach($cols_width as $key=>$width) {
            if($width > 0){
                $sheet->getColumnDimension(trans('site::excel.col.'.$key))->setWidth($width);
            } else {
                $sheet->getColumnDimension(trans('site::excel.col.'.$key))->setAutoSize(true);            
            }

        
        }
        
        $users = User::query()
                    ->whereHas('tenders')
                    ->orderBy('name')->get();
                    
        foreach($users as $user) {
            
           
                $sheet->getStyle('A' . $row_num .':P' .$row_num)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ade4f0');
                $column=1;
                $sheet
                        ->setCellValue(trans('site::excel.col.'.$column) . $row_num, $user->name)->getStyle(trans('site::excel.col.'.$column) . $row_num)
                        ->getAlignment()->setHorizontal('left');
                $column++;
                $row_num++;
                
                 foreach ($this->repository->all()->where('manager_id',$user->id) as $key => $tender) {
                     $this->_buildTender($sheet, $tender, $row_num);
                     $row_num++;
                    
                 } 
                 $row_num++;
           
           
        
        }
        $row_num++;
      
        
    
    
    }
    
    
    
    private function _buildTender($sheet, Tender $tender, $row_num)
    {	
        
        $column=1;
        
                $sheet->setCellValue(trans('site::excel.col.'.$column) . $row_num, $tender->id);
                    $sheet->getStyle(trans('site::excel.col.'.$column) . $row_num)->getAlignment()->setHorizontal('left');
                
                $column++;
                
                //2     
                $sheet
                    ->setCellValue(trans('site::excel.col.'.$column) . $row_num, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($tender->getAttribute('created_at')))
                    ->getStyle(trans('site::excel.col.'.$column) . $row_num)
                    ->getNumberFormat()
                    ->setFormatCode('dd.mm.yyyy');
                    $sheet->getStyle(trans('site::excel.col.'.$column) . $row_num)->getAlignment()->setHorizontal('center');
                
                
                $column++; //3
                $sheet->setCellValue(trans('site::excel.col.'.$column) . $row_num, $tender->city)
                        ->getStyle(trans('site::excel.col.'.$column) . $row_num)->getAlignment()->setHorizontal('left');
                
                $column++;//4
                $sheet->setCellValue(trans('site::excel.col.'.$column) . $row_num, $tender->distributor->name)
                        ->getStyle(trans('site::excel.col.'.$column) . $row_num)->getAlignment()->setHorizontal('left');
                
                
                $column++;//5
                $sheet->setCellValue(trans('site::excel.col.'.$column) . $row_num, $tender->planned_purchase_year .' '.trans('site::messages.months_cl.' .$tender->planned_purchase_month))
                        ->getStyle(trans('site::excel.col.'.$column) . $row_num)->getAlignment()->setHorizontal('left');
                
                
                $column++;//6
                $sheet->setCellValue(trans('site::excel.col.'.$column) . $row_num, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($tender->date_price->format('d.m.Y')))
                    ->getStyle(trans('site::excel.col.'.$column) . $row_num)
                    ->getNumberFormat()
                    ->setFormatCode('dd.mm.yyyy');
                    $sheet->getStyle(trans('site::excel.col.'.$column) . $row_num)->getAlignment()->setHorizontal('left');
                    
                $column++;//7
                $sheet->setCellValue(trans('site::excel.col.'.$column) . $row_num, round($tender->rates,2))
                        ->getStyle(trans('site::excel.col.'.$column) . $row_num)->getAlignment()->setHorizontal('left');
                $items='';
                foreach($tender->tenderProducts as $ki=>$item){
                    if($ki>1){$items=$items .chr(10);}
                    $items=$items .$item->product->name .'x' .$item->count .' €' .round($item->price_distr_euro,0) .' (' .round($item->discount,0) .'%)';
                
                }
                
                   
                $column++;//8
                $sheet->setCellValue(trans('site::excel.col.'.$column) . $row_num, $items)
                        ->getStyle(trans('site::excel.col.'.$column) . $row_num)->getAlignment()->setHorizontal('left');
                    $sheet->setCellValue(trans('site::excel.col.'.$column) . $row_num, $items)
                        ->getStyle(trans('site::excel.col.'.$column) . $row_num)->getAlignment()->setWrapText(true);
                
                   
                $column++;//9
                $sheet->setCellValue(trans('site::excel.col.'.$column) . $row_num, $tender->status->name)
                        ->getStyle(trans('site::excel.col.'.$column) . $row_num)->getAlignment()->setHorizontal('left');
                    $sheet->getStyle(trans('site::excel.col.'.$column) . $row_num)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB(substr($tender->status->color,1,6));
      
                  
                $column++;//10
                $sheet->setCellValue(trans('site::excel.col.'.$column) . $row_num, $tender->rivals)
                        ->getStyle(trans('site::excel.col.'.$column) . $row_num)->getAlignment()->setHorizontal('left');
                  
                $column++;//11
                $sheet->setCellValue(trans('site::excel.col.'.$column) . $row_num, $tender->comments)
                        ->getStyle(trans('site::excel.col.'.$column) . $row_num)->getAlignment()->setHorizontal('left');
                   
                
                $column++;//12
                $sheet->setCellValue(trans('site::excel.col.'.$column) . $row_num, $tender->result)
                        ->getStyle(trans('site::excel.col.'.$column) . $row_num)->getAlignment()->setHorizontal('left');
                   
                
                $column++;//13
                $sheet->setCellValue(trans('site::excel.col.'.$column) . $row_num, trans('site::tender.tender_source.'.$tender->source_id))
                        ->getStyle(trans('site::excel.col.'.$column) . $row_num)->getAlignment()->setHorizontal('left');
                   
                $column++;//14
                $sheet->setCellValue(trans('site::excel.col.'.$column) . $row_num, $tender->address .' ' .$tender->address_addon)
                        ->getStyle(trans('site::excel.col.'.$column) . $row_num)->getAlignment()->setHorizontal('left');
               
                $column++;//15
                $sheet->setCellValue(trans('site::excel.col.'.$column) . $row_num, $tender->address_name)
                        ->getStyle(trans('site::excel.col.'.$column) . $row_num)->getAlignment()->setHorizontal('left');
                   
                $column++;//16
                $sheet->setCellValue(trans('site::excel.col.'.$column) . $row_num, $tender->category->name)
                        ->getStyle(trans('site::excel.col.'.$column) . $row_num)->getAlignment()->setHorizontal('left');
                   
               
                
        	
    }
    
    
    public function tendersMonth($month, $tenders, $year = '2021')
	{   
        
        $first_day = Carbon::createFromDate($year, $month, 1)->format('Y-m-d');
	    $last_day = Carbon::createFromDate($year, $month,1)->endOfMonth()->format('Y-m-d');
        $mss=$tenders;
       
        $ms=Tender::whereIn('id',$mss->pluck('id')); 
        $ums = $ms->where('date_from', '<=',$last_day)->where('date_from', '>=',$first_day);
        //->whereHas('tenderUsers', function ($q) {$q->where('user_id', $this->id);})
        
      
       
		return $ums;
	}
    public function budgetRubMonth($month, $tenders, $year = '2021')
	{   
        
        $first_day = Carbon::createFromDate($year, $month, 1)->format('Y-m-d');
	    $last_day = Carbon::createFromDate($year, $month,1)->endOfMonth()->format('Y-m-d');
        $mss=$tenders;
        $budget=0;
        foreach(Tender::whereIn('id',$mss->pluck('id'))->where('date_from', '<=',$last_day)->where('date_from', '>=',$first_day)->get() as $mst){
            $budget+=$mst->budget_rub;
        }
        return $budget;
	}
    public function budgetEurMonth($month, $tenders, $year = '2021')
	{   
        
        $first_day = Carbon::createFromDate($year, $month, 1)->format('Y-m-d');
	    $last_day = Carbon::createFromDate($year, $month,1)->endOfMonth()->format('Y-m-d');
        $mss=$tenders;
        $budget=0;
        foreach(Tender::whereIn('id',$mss->pluck('id'))->where('date_from', '<=',$last_day)->where('date_from', '>=',$first_day)->get() as $mst){
            $budget+=$mst->budget_eur;
        }
        return $budget;
	}
}
