<?php

namespace ServiceBoiler\Prf\Site\Exports\Excel;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use ServiceBoiler\Prf\Site\Models\Act;
use ServiceBoiler\Prf\Site\Models\Event;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\Region;
use ServiceBoiler\Prf\Site\Models\Result;
use ServiceBoiler\Prf\Site\Models\RegionItalyDistrict;
use ServiceBoiler\Prf\Site\Models\Repair;
use ServiceBoiler\Prf\Site\Models\Storehouse;
use ServiceBoiler\Prf\Site\Support\Excel;

class AscYearExcel extends Excel
{
    /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet Sheet */
    private $_sheet;
    private $_sheet2;

    function build()
    {
        $regions=Region::where('country_id','643')->orderBy('notification_address')->orderBy('name')->get();
        $periods=$this->periods;
        $this->getProperties()->setTitle('Отчет по АСЦ');
        $this->getActiveSheet()->setTitle('ASC');
        $this->_sheet = $this->getActiveSheet();
      
        $count = 1;
        $lcount = 1;
        
        $this->_sheet->getColumnDimension('A')->setWidth(23);
        $this->_sheet->getColumnDimension('B')->setWidth(28);
        $this->_sheet->getColumnDimension('C')->setWidth(7);
        $this->_sheet->getColumnDimension('D')->setWidth(7);
        $this->_sheet->getColumnDimension('F')->setWidth(12);
        $this->_sheet->getColumnDimension('I')->setWidth(11);
        $this->_sheet->getColumnDimension('J')->setWidth(11);
        $this->_sheet->getColumnDimension('K')->setWidth(11);
        $this->_sheet->getColumnDimension('L')->setWidth(11);
        $this->_sheet->getColumnDimension('M')->setWidth(11);
        $this->_sheet->getColumnDimension('N')->setWidth(11);
        $this->_sheet->getColumnDimension('O')->setWidth(11);
        $this->_sheet->getColumnDimension('P')->setWidth(11);
        $this->_sheet->getColumnDimension('Q')->setWidth(11);
        $this->_sheet->getColumnDimension('R')->setWidth(15);
        $this->_sheet->getColumnDimension('T')->setWidth(12);
        $this->_sheet->getColumnDimension('U')->setWidth(12);
        $this->_sheet->getColumnDimension('V')->setWidth(12);
        $this->_sheet->getColumnDimension('W')->setWidth(12);
        $this->_sheet->getColumnDimension('X')->setWidth(12);
        $this->_sheet->getColumnDimension('V')->setWidth(12);
        
        
         $this->_sheet
            ->setCellValue('A' . $count, 'Менеджер')
            ->setCellValue('B' . $count, 'Регион')
            ->setCellValue('C' . $count, 'Кол-во АСЦ на нач.года')
            ->setCellValue('D' . $count, 'Кол-во АСЦ')
            ->setCellValue('E' . $count, 'АСЦ с дог.')
            ->setCellValue('F' . $count, 'Кол-во Инженеров')
            ->setCellValue('G' . $count, 'Инж. привязанных')
            ->setCellValue('H' . $count, 'Инж. с серт.')
            ->setCellValue('I' . $count, 'АРГ Прислано за год')
            ->setCellValue('J' . $count, 'АРГ Отклонен')
            ->setCellValue('K' . $count, 'АРГ Удален')
            ->setCellValue('L' . $count, 'АРГ Новые/Ожидание')
            ->setCellValue('M' . $count, 'АРГ Одобрено')
            ->setCellValue('N' . $count, 'АРГ Оплачено')
            ->setCellValue('O' . $count, 'Cумма оплаченых АГР')
            ->setCellValue('P' . $count, 'Кол-во Складов ФАКТ')
            ->setCellValue('Q' . $count, 'Выгрузка ЗЧ')
            ->setCellValue('R' . $count, 'Cебестоимость остатков (EUR)')
            ->setCellValue('T' . $count, 'Обучений за 1 квартал')
            ->setCellValue('U' . $count, 'Обучений за 2 квартал')
            ->setCellValue('V' . $count, 'Обучений за 3 квартал')
            ->setCellValue('W' . $count, 'Обучений за 4 квартал')
            ->setCellValue('X' . $count, 'Обучений за год')
            ;
        $head=array('A1','B1','C1','D1','E1','F1','G1','H1','I1','J1','K1','L1','M1','N1','O1','P1','Q1','R1','S1','T1','U1','V1','W1','X1');
        foreach($head as $cell){
            $this->_sheet->getStyle($cell)->getFont()->setBold(true);
            $this->_sheet->getStyle($cell)->getAlignment()->setWrapText(true);
        }
        $count++;
        
        $ascs_year=Result::where('type_id','asc_count')->where('date_actual',Carbon::now()->startOfYear()->format('Y-m-d'))->first()->value_int;
        
        $ascs=User::whereHas('roles', function ($query){
                $query->whereIn('name', ['asc','csc']);
            })->where('active','1')->get();
         
        $engeneers=User::whereHas('roles', function ($query){
                $query->whereIn('name', ['service_fl']);
            })->where('active','1')->get();
        
        $cscs=User::whereHas('roles', function ($query){
                $query->whereIn('name', ['csc']);
            })->where('active','1')->get();
        $engeneers_attached=User::whereHas('parents', function ($query) {
                                                        $query->whereHas('roles', function ($query) 
                                                                                            {$query->whereIn('name', ['asc','csc']);});})
                            ->whereHas('roles', function ($query){$query->whereIn('name', ['service_fl']);})
                            ->where('active','1')->get();

        $engeneers_cert=User::whereHas('parents', function ($query) {
                                                                $query->whereHas('roles', function ($query) 
                                                                                                    {$query->whereIn('name', ['asc','csc']);});})
                                    ->whereHas('certificates', function ($query) {
                                                                $query->where('type_id','1');
                                                                })
                                    ->where('active','1')->get();
        
        $ascs_with_contract=User::whereHas('roles', function ($query){$query->whereIn('name', ['asc','csc']);})
                                        ->where('active','1')->whereHas('contragents', function ($query) {
                                                                    $query->where('contract','!=','');})->get();
        
    
        
         $repairs=Repair::where('created_at','>=',$this->year .'.01.01')->where('created_at','<=',$this->year .'.12.31')->get();
         $repairs_approved=Repair::where('created_at','>=',$this->year .'.01.01')->where('created_at','<=',$this->year .'.12.31')->where('status_id','5')->get();
         $repairs_declined=Repair::where('created_at','>=',$this->year .'.01.01')->where('created_at','<=',$this->year .'.12.31')->where('status_id','4')->get();
         $repairs_deleted=Repair::where('created_at','>=',$this->year .'.01.01')->where('created_at','<=',$this->year .'.12.31')->where('status_id','6')->get();
         $repairs_wait=Repair::where('created_at','>=',$this->year .'.01.01')->where('created_at','<=',$this->year .'.12.31')->whereIn('status_id',['1','2','3']);
         $repairs_paid=Repair::where('created_at','>=',$this->year .'.01.01')->where('created_at','<=',$this->year .'.12.31')
            ->where('status_id','5')->whereHas('act', function ($query) {
                $query->where('paid','1');
            });

         $repairs_money=$repairs_paid->get()->sum('total_difficulty_cost')+$repairs_paid->get()->sum('total_distance_cost')+$repairs_paid->get()->sum('total_cost_parts');
         $storehouses=Storehouse::whereIn('user_id',$cscs->pluck('id'))->get();
         
         $events_1q=Event::whereIn('status_id',['2','3'])->where('date_from','>=',$this->year .'.01.01')->where('date_from','<=',$this->year .'.03.31')->get();
            $events_2q=Event::whereIn('status_id',['2','3'])->where('date_from','>=',$this->year .'.04.01')->where('date_from','<=',$this->year .'.06.30')->get();
            $events_3q=Event::whereIn('status_id',['2','3'])->where('date_from','>=',$this->year .'.07.01')->where('date_from','<=',$this->year .'.09.30')->get();
            $events_4q=Event::whereIn('status_id',['2','3'])->where('date_from','>=',$this->year .'.09.01')->where('date_from','<=',$this->year .'.12.31')->get();
             
         $this->_sheet
           ->setCellValue('B' . $count, 'Всего за ' .$this->year)
           ->setCellValue('C' . $count, $ascs_year)
           ->setCellValue('D' . $count, $ascs->count())
           ->setCellValue('E' . $count, $ascs_with_contract->count())
            ->setCellValue('F' . $count, $engeneers->count())
            ->setCellValue('G' . $count, $engeneers_attached->count())
            ->setCellValue('H' . $count, $engeneers_cert->count())
            ->setCellValue('I' . $count, $repairs->count())
            ->setCellValue('J' . $count, $repairs_declined->count())
            ->setCellValue('K' . $count, $repairs_deleted->count())
            ->setCellValue('L' . $count, $repairs_wait->count())
            ->setCellValue('M' . $count, $repairs_approved->count())
            ->setCellValue('N' . $count, $repairs_paid->count())
            ->setCellValue('O' . $count, round($repairs_money),0)
            ->setCellValue('P' . $count, $storehouses->count())
           // ->setCellValue('R' . $count, round($storehouses->sum('total_cost_products'),0));
            ->setCellValue('R' . $count, $storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_all_summ',$periods['today']));
         
            $this->_sheet->getStyle('O' . $count)->getNumberFormat()->setFormatCode('# ##0');
             $this->_sheet->getStyle('R' . $count)->getNumberFormat()->setFormatCode('# ##0');
             
             $this->_sheet->setCellValue('X' . $count, $events_1q->count() + $events_2q->count() + $events_3q->count() + $events_4q->count());
             $this->_sheet->setCellValue('T' . $count, $events_1q->count());
             $this->_sheet->setCellValue('U' . $count, $events_2q->count());
             $this->_sheet->setCellValue('U' . $count, $events_2q->count());
             $this->_sheet->setCellValue('V' . $count, $events_3q->count());
             $this->_sheet->setCellValue('W' . $count, $events_4q->count());
             
        
        
         
        $count++;
        foreach($regions as $key=>$region) {
            $count++;
            $lcount++;
            
            
	        $manager=User::where('email',$region->notification_address)->first();
            $ascs=User::whereHas('addresses', function ($query)  use ($region){
                $query->where(DB::raw('region_id'), $region->id);
            })->whereHas('roles', function ($query){
                $query->whereIn('name', ['asc','csc']);
            })->where('active','1')->get();
            
            $engeneers=User::whereHas('addresses', function ($query)  use ($region){
                $query->where(DB::raw('region_id'), $region->id);
            })->whereHas('roles', function ($query){
                $query->whereIn('name', ['service_fl']);
            })->where('active','1')->get();
            
            $cscs=User::whereHas('roles', function ($query){
                $query->whereIn('name', ['csc']);
            })->where('active','1')->get();
            
            
            $engeneers_attached=User::whereHas('addresses', function ($query)  use ($region){
                $query->where(DB::raw('region_id'), $region->id);
            })->whereHas('parents', function ($query) {
                                                            $query->whereHas('roles', function ($query) 
                                                                                                {$query->whereIn('name', ['asc','csc']);});})
                                ->whereHas('roles', function ($query){$query->whereIn('name', ['service_fl']);})
                                ->where('active','1')->get();

            $engeneers_cert=User::whereHas('addresses', function ($query)  use ($region){
                $query->where(DB::raw('region_id'), $region->id);
            })->whereHas('parents', function ($query) {
                                                                    $query->whereHas('roles', function ($query) 
                                                                                                        {$query->whereIn('name', ['asc','csc']);});})
                                        ->whereHas('certificates', function ($query) {
                                                                    $query->where('type_id','1');
                                                                    })
                                        ->where('active','1')->get();
            
            $ascs_with_contract=User::whereHas('addresses', function ($query)  use ($region){
                $query->where(DB::raw('region_id'), $region->id);
            })->whereHas('roles', function ($query){$query->whereIn('name', ['asc','csc']);})
                                            ->where('active','1')->whereHas('contragents', function ($query) {
                                                                        $query->where('contract','!=','');})->get();
        
            
            
            
            $repairs=Repair::whereIn('user_id', $ascs->pluck('id'))->where('created_at','>=',$this->year .'.01.01')->where('created_at','<=',$this->year .'.12.31');
            
            $repairs_approved=Repair::whereIn('user_id', $ascs->pluck('id'))->where('created_at','>=',$this->year .'.01.01')->where('created_at','<=',$this->year .'.12.31')
            ->where('status_id','5');
            
            $repairs_declined=Repair::whereIn('user_id', $ascs->pluck('id'))->where('created_at','>=',$this->year .'.01.01')->where('created_at','<=',$this->year .'.12.31')
            ->where('status_id','4');
            
            $repairs_deleted=Repair::whereIn('user_id', $ascs->pluck('id'))->where('created_at','>=',$this->year .'.01.01')->where('created_at','<=',$this->year .'.12.31')
            ->where('status_id','6');
            
            $repairs_wait=Repair::whereIn('user_id', $ascs->pluck('id'))->where('created_at','>=',$this->year .'.01.01')->where('created_at','<=',$this->year .'.12.31')
            ->whereIn('status_id',['1','2','3']);
            
            $repairs_paid=Repair::whereIn('user_id', $ascs->pluck('id'))->where('created_at','>=',$this->year .'.01.01')->where('created_at','<=',$this->year .'.12.31')
            ->where('status_id','5')->whereHas('act', function ($query) {
                $query->where('paid','1');
            });
            
            $repairs_money=$repairs_paid->get()->sum('total_difficulty_cost')+$repairs_paid->get()->sum('total_distance_cost')+$repairs_paid->get()->sum('total_cost_parts');
            
            $storehouses=Storehouse::whereIn('user_id',$cscs->pluck('id'))->where('enabled','1')->whereHas('user', function ($query)  use ($region){
                $query->where('region_id', $region->id);
            })->get();
            
            
            //$events_y=Event::whereIn('status_id',['2','3'])->where('region_id',$region->id)->where('date_from','>=',$this->year .'.01.01')->where('date_from','<=',$this->year .'.12.31')->get();
            $events_1q=Event::whereIn('status_id',['2','3'])->where('region_id',$region->id)->where('date_from','>=',$this->year .'.01.01')->where('date_from','<=',$this->year .'.03.31')->get();
            $events_2q=Event::whereIn('status_id',['2','3'])->where('region_id',$region->id)->where('date_from','>=',$this->year .'.04.01')->where('date_from','<=',$this->year .'.06.30')->get();
            $events_3q=Event::whereIn('status_id',['2','3'])->where('region_id',$region->id)->where('date_from','>=',$this->year .'.07.01')->where('date_from','<=',$this->year .'.09.30')->get();
            $events_4q=Event::whereIn('status_id',['2','3'])->where('region_id',$region->id)->where('date_from','>=',$this->year .'.09.01')->where('date_from','<=',$this->year .'.12.31')->get();
            
            
            //dd($storehouses->count());
            //dd($storehouses->sum('total_cost_products'));
           
            $this->_sheet
            ->setCellValue('A' . $count, $manager->name)
            ->setCellValue('B' . $count, $region->getAttribute('name'))
            ->setCellValue('D' . $count, $ascs->count())
            ->setCellValue('E' . $count, $ascs_with_contract->count())
            ->setCellValue('F' . $count, $engeneers->count())
            ->setCellValue('G' . $count, $engeneers_attached->count())
            ->setCellValue('H' . $count, $engeneers_cert->count())
            ->setCellValue('I' . $count, $repairs->count())
            ->setCellValue('J' . $count, $repairs_declined->count())
            ->setCellValue('K' . $count, $repairs_deleted->count())
            ->setCellValue('L' . $count, $repairs_wait->count())
            ->setCellValue('M' . $count, $repairs_approved->count())
            ->setCellValue('N' . $count, $repairs_paid->count())
            ->setCellValue('O' . $count, round($repairs_money),0)
            ->setCellValue('P' . $count, $storehouses->count());
             if($storehouses->count()) {
                 $this->_sheet
                 ->setCellValue('R' . $count, $storehouses->sum('total_cost_products_all_today'));
             }
             $this->_sheet->getStyle('O' . $count)->getNumberFormat()->setFormatCode('# ##0');
             $this->_sheet->getStyle('R' . $count)->getNumberFormat()->setFormatCode('# ##0');
             
             $this->_sheet->setCellValue('X' . $count, $events_1q->count() + $events_2q->count() + $events_3q->count() + $events_4q->count());
             $this->_sheet->setCellValue('T' . $count, $events_1q->count());
             $this->_sheet->setCellValue('U' . $count, $events_2q->count());
             $this->_sheet->setCellValue('V' . $count, $events_3q->count());
             $this->_sheet->setCellValue('W' . $count, $events_4q->count());
             
             
             
             if($lcount%2==1) {
             $cells=array('A'.$count,'B'.$count,'D'.$count,'E'.$count,'F'.$count,'G'.$count,'H'.$count,'I'.$count,'J'.$count,'K'.$count,'L'.$count,'M'.$count,'N'.$count,'O'.$count,'P'.$count,'Q'.$count,'R'.$count,'S'.$count,'T'.$count,'U'.$count,'V'.$count,'W'.$count,'X'.$count,'V'.$count,'W'.$count);
             foreach($cells as $cell){
                        $this->_sheet->getStyle($cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('fdfdaa');
                    }
                }
            foreach($storehouses as $storehouse) {
                $count++;
                //dd($storehouse->user->name);
                $cells=array('A'.$count,'B'.$count,'D'.$count,'E'.$count,'F'.$count,'G'.$count,'H'.$count,'I'.$count,'J'.$count,'K'.$count,'L'.$count,'M'.$count,'N'.$count,'O'.$count,'P'.$count,'Q'.$count,'R'.$count,'S'.$count,'T'.$count,'U'.$count,'V'.$count,'W'.$count,'X'.$count,'V'.$count,'W'.$count);
                    foreach($cells as $cell){
                        $this->_sheet->getStyle($cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('cccccc');
                    }
            
                $this->getActiveSheet()->getRowDimension($count)->setOutlineLevel(1);
                $this->getActiveSheet()->getRowDimension($count)->setVisible(false);
                
                $this->_sheet->setCellValue('B' . $count, $storehouse->user->name);
//                $this->_sheet->setCellValue('D' . $count, $storehouse->user->repairs->count());
                $this->_sheet->setCellValue('F' . $count, $storehouse->user->childEngineers->count());
                $this->_sheet->setCellValue('I' . $count, $repairs->where('user_id',$storehouse->user->id)->count());
                
                $this->_sheet->setCellValue('L' . $count, $repairs_wait->where('user_id',$storehouse->user->id)->count());
                $this->_sheet->setCellValue('M' . $count, $repairs_approved->where('user_id',$storehouse->user->id)->count());
                $this->_sheet->setCellValue('N' . $count, $repairs_paid->where('user_id',$storehouse->user->id)->count());
                $this->_sheet->setCellValue('O' . $count, round($repairs_paid->where('user_id',$storehouse->user->id)->get()->sum('total_difficulty_cost')+$repairs_paid->get()->sum('total_distance_cost')+$repairs_paid->get()->sum('total_cost_parts'),0));
                if($storehouse->everyday && $storehouse->uploaded_at > Carbon::now()->subDays(60)) {
                    $this->_sheet->setCellValue('Q' . $count, 'авто');
                    $this->_sheet->getStyle('Q' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('6cff58');
                } elseif($storehouse->uploaded_at > Carbon::now()->subDays(60)){
                    $this->_sheet->setCellValue('Q' . $count, 'вручную');
                    $this->_sheet->getStyle('Q' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('eae700');
                } else {
                    $this->_sheet->setCellValue('Q' . $count, 'НЕТ');
                    $this->_sheet->getStyle('Q' . $count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ff6dde');
                }
                $this->_sheet->setCellValue('R' . $count, $storehouse->cost_products_type_to_date('storehouse_all_summ',$periods['today']));
                
                $this->_sheet->getStyle('O' . $count)->getNumberFormat()->setFormatCode('# ##0');
                $this->_sheet->getStyle('R' . $count)->getNumberFormat()->setFormatCode('# ##0');
                
                
                
                
            }
            
        }
 /*    
        $this->_sheet2 = $this->createSheet(2);
       
        $this->_sheet2->setTitle("Округа");
        
        foreach(RegionItalyDistrict::get() as $itd){
            foreach($itd->regions as $region) {
                $count++;
                $manager=User::where('email',$region->notification_address)->first();
                $repairs=Repair::whereIn('user_id', $ascs->pluck('id'))->where('created_at','>=',$this->year .'.01.01')->where('created_at','<=',$this->year .'.12.31');
                
                $repairs_approved=Repair::whereIn('user_id', $ascs->pluck('id'))->where('created_at','>=',$this->year .'.01.01')->where('created_at','<=',$this->year .'.12.31')
                ->where('status_id','5');
                
                $repairs_paid=Repair::whereIn('user_id', $ascs->pluck('id'))->where('created_at','>=',$this->year .'.01.01')->where('created_at','<=',$this->year .'.12.31')
                ->where('status_id','5')->whereHas('act', function ($query) {
                    $query->where('paid','1');
                });
                
                
                
            
            
            }
            
        }
     */
    
    }
    public function setYear($year = null)
    {
        $this->year = $year;

        return $this;
    }
    
    public function setPeriods($periods = array())
    {
        $this->periods = $periods;

        return $this;
    }
}
