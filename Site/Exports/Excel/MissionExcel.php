<?php

namespace ServiceBoiler\Prf\Site\Exports\Excel;

use Carbon\Carbon;
use ServiceBoiler\Prf\Site\Models\Mission;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Support\Excel;

class MissionExcel extends Excel
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
        $sheet->setTitle(trans('site::messages.months.' .date("m", strtotime("+$i month"))));
        $this->_buildSheet ($sheet, $i);
        $i=1;    
        $this->_sheet = $this->createSheet(1);
        $this->setActiveSheetIndex(1);
        
        $sheet = $this->getActiveSheet();
        $sheet->setTitle(trans('site::messages.months.' .date("m", strtotime("+$i month"))));
        $this->_buildSheet ($sheet, $i);
        $i=-1;    
        $this->_sheet = $this->createSheet(2);
        $this->setActiveSheetIndex(2);
        
        $sheet = $this->getActiveSheet();
        $sheet->setTitle(trans('site::messages.months.' .date("m", strtotime("+$i month"))));
        $this->_buildSheet ($sheet, $i);
    }

    
    private function _buildSheet ($sheet, $i=0) {
        $sheet->getStyle('A1:L1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('b6f2a0');
      
        foreach (trans('site::excel.mission') as $cell => $value) {
            $sheet->setCellValue(trans('site::excel.col.'.$cell).'1', $value)->getStyle(trans('site::excel.col.'.$cell).'1')->getFont(trans('site::excel.col.'.$cell).'1')->setBold(true);
        }
        $count = 2;
        $column = 1;
        $second = 0;
        $sheet->getStyle('A:Z')->getAlignment()->setVertical('center');
        $sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(5);
            $column++;
        //2
        $sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(30);
            $column++;
        //3
        $sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(16);
                    $sheet->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('left');
            $column++;
        //4
        $sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(11);
            $column++;
        //5
        $sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(11);
            $column++;
        //6
        $sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(11);
        
            $column++;
        //7
        $sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(17);
        $sheet->getStyle(trans('site::excel.col.'.$column))->getAlignment()->setVertical('center');
            $column++;
        //8
        $sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(40);
            $column++;
        //9
        $sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
            $column++;
        //10
        $sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
            $column++;
        //11
        $sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(40);
            $column++;
        $sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(40);
            $column++;
        $sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
            $column++;
        $sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
            $column++;
        $sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
       
        
        $users = User::query()->whereHas('roles', function ($q) {
                                                $q->whereHas('permissions', function ($q) {$q->where('name','admin_missions_create');});
                                })
                    ->whereHas('missions')
                    ->orderBy('name')->get();
        foreach($users as $user) {
            
            if($user->missionsMonth(date("m", strtotime("+$i month")),$this->repository->all())->count()>0) {
                $sheet->getStyle('A' . $count .':L' .$count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ade4f0');
                $column=2;
                $sheet
                        ->setCellValue(trans('site::excel.col.'.$column) . $count, $user->name)->getStyle(trans('site::excel.col.'.$column) . $count)
                        ->getAlignment()->setHorizontal('left');
                $column++;
                $column++;
                $sheet
                        ->setCellValue(trans('site::excel.col.'.$column) . $count, trans('site::messages.months.' .date("m", strtotime("+$i month"))))->getStyle(trans('site::excel.col.'.$column) . $count)
                        ->getAlignment()->setHorizontal('left');
                $count++;
                
                foreach ($user->missionsMonth(date("m", strtotime("+$i month")),$this->repository->all())->get() as $key => $mission) {
                    $this->_buildMission($sheet, $mission, $count);
                    $count++;
                    
                } 
                $count++;
            }
           
        
        }
        $count++;
      
        $count++;
        $sheet->setCellValue(trans('site::excel.col.2') . $count, 'Общий бюджет на '.trans('site::messages.months.' .date("m", strtotime("+$i month"))) .':')->getStyle(trans('site::excel.col.'.$column) . $count)
                ->getAlignment()->setHorizontal('left');
                
        
                
        $sheet->setCellValue(trans('site::excel.col.3') . $count, $this->budgetRubMonth(date("m", strtotime("+$i month")),$this->repository->all()))->getStyle(trans('site::excel.col.'.$column) . $count)
                ->getAlignment()->setHorizontal('left');
        $sheet->getStyle(trans('site::excel.col.3') . $count)->getNumberFormat()->setFormatCode(' # ##0\ \R\U\B');
        
                
        $sheet->setCellValue(trans('site::excel.col.4') . $count, $this->budgetEurMonth(date("m", strtotime("+$i month")),$this->repository->all()))->getStyle(trans('site::excel.col.'.$column) . $count)
                ->getAlignment()->setHorizontal('left');
        $sheet->getStyle(trans('site::excel.col.4') . $count)->getNumberFormat()->setFormatCode(' # ##0\ \E\U\R');
        
        $count++;
    
    
    }
    
    
    
    private function _buildMission($sheet, Mission $mission, $count)
    {	
        
        $column=1;
        
                $sheet
                    ->setCellValue(trans('site::excel.col.'.$column) . $count, $mission->id);
                
                $column++;
                $column++;
                $sheet
                    ->setCellValue(trans('site::excel.col.'.$column) . $count, $mission->division_id ? $mission->division->name : 'не указано')->getStyle(trans('site::excel.col.'.$column) . $count)
                    ->getAlignment()->setHorizontal('left');
                    
                $column++;        
                $column++;        
                $sheet
                    ->setCellValue(trans('site::excel.col.'.$column) . $count, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($mission->getAttribute('date_from')))
                    ->getStyle(trans('site::excel.col.'.$column) . $count)
                    ->getNumberFormat()
                    ->setFormatCode('dd.mm.yyyy');
                    $sheet->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('center');
                
                $column++;        
                $sheet
                    ->setCellValue(trans('site::excel.col.'.$column) . $count, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($mission->getAttribute('date_to')))
                    ->getStyle(trans('site::excel.col.'.$column) . $count)
                    ->getNumberFormat()
                    ->setFormatCode('dd.mm.yyyy');
                    $sheet->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('center');
                
                
                $column++;
                if(!empty($mission->locality)){
                $sheet
                    ->setCellValue(trans('site::excel.col.'.$column) . $count, $mission->locality)->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('center');
                }
                
                $column++;
                if(!empty($mission->target)){
                $sheet
                    ->setCellValue(trans('site::excel.col.'.$column) . $count, $mission->target)
                    ->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('center');
                $sheet
                    ->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setWrapText(true);
                }
                $column++;
                if(!empty($mission->budget)){
                    $sheet
                        ->setCellValue(trans('site::excel.col.'.$column) . $count, $mission->budget)
                        ->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('left');
                    $sheet->getStyle(trans('site::excel.col.'.$column) . $count)->getNumberFormat()->setFormatCode($mission->budgetCurrency->symbol_right .' # ##0');
                }
                $column++;
                if($mission->budgetCurrency->id != 978) {
                
                    $sheet
                        ->setCellValue(trans('site::excel.col.'.$column) . $count, round( $mission->budget/$mission->budget_currency_eur_rate,0))
                        ->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('left');
                    $sheet->getStyle(trans('site::excel.col.'.$column) . $count)->getNumberFormat()->setFormatCode('\€\ # ##0');
                }
                
            
        
                $column++;
                $sheet
                    ->setCellValue(trans('site::excel.col.'.$column) . $count, $mission->comments)->getStyle(trans('site::excel.col.'.$column) . $count)
                    ->getAlignment()->setHorizontal('center');
        
                $column++;
                $sheet
                    ->setCellValue(trans('site::excel.col.'.$column) . $count, $mission->result)->getStyle(trans('site::excel.col.'.$column) . $count)
                    ->getAlignment()->setHorizontal('center');
        
                $column++;
                $sheet
                    ->setCellValue(trans('site::excel.col.'.$column) . $count, $mission->status->name)->getStyle(trans('site::excel.col.'.$column) . $count)
                    ->getAlignment()->setHorizontal('center');
                
        	
    }
    
    
    public function missionsMonth($month, $missions, $year = '2021')
	{   
        
        $first_day = Carbon::createFromDate($year, $month, 1)->format('Y-m-d');
	    $last_day = Carbon::createFromDate($year, $month,1)->endOfMonth()->format('Y-m-d');
        $mss=$missions;
       
        $ms=Mission::whereIn('id',$mss->pluck('id')); 
        $ums = $ms->where('date_from', '<=',$last_day)->where('date_from', '>=',$first_day);
        //->whereHas('missionUsers', function ($q) {$q->where('user_id', $this->id);})
        
      
       
		return $ums;
	}
    public function budgetRubMonth($month, $missions, $year = '2021')
	{   
        
        $first_day = Carbon::createFromDate($year, $month, 1)->format('Y-m-d');
	    $last_day = Carbon::createFromDate($year, $month,1)->endOfMonth()->format('Y-m-d');
        $mss=$missions;
        $budget=0;
        foreach(Mission::whereIn('id',$mss->pluck('id'))->where('date_from', '<=',$last_day)->where('date_from', '>=',$first_day)->get() as $mst){
            $budget+=$mst->budget_rub;
        }
        return $budget;
	}
    public function budgetEurMonth($month, $missions, $year = '2021')
	{   
        
        $first_day = Carbon::createFromDate($year, $month, 1)->format('Y-m-d');
	    $last_day = Carbon::createFromDate($year, $month,1)->endOfMonth()->format('Y-m-d');
        $mss=$missions;
        $budget=0;
        foreach(Mission::whereIn('id',$mss->pluck('id'))->where('date_from', '<=',$last_day)->where('date_from', '>=',$first_day)->get() as $mst){
            $budget+=$mst->budget_eur;
        }
        return $budget;
	}
}
