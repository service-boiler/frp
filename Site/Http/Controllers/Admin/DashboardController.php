<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use ServiceBoiler\Prf\Site\Exports\Excel\AscYearExcel;
use ServiceBoiler\Prf\Site\Models\Act;
use ServiceBoiler\Prf\Site\Models\Event;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\Region;
use ServiceBoiler\Prf\Site\Models\RegionItalyDistrict;
use ServiceBoiler\Prf\Site\Models\Repair;
use ServiceBoiler\Prf\Site\Models\Result;
use ServiceBoiler\Prf\Site\Models\Storehouse;
use ServiceBoiler\Prf\Site\Models\Authorization;

class DashboardController extends Controller
{

	use AuthorizesRequests;
    
 
    public function index(Request $request)
	{
        return view('site::admin.dashboard.index');
    }
    
    
   
    public function tenders(Request $request)
	{
    $years = [2018,Carbon::now()->addYears(1)->format('Y'),Carbon::now()->format('Y')];
    $today = Carbon::now()->format('Y-m-d');
    $request->input('year') ? $year=$request->input('year') : $year=Carbon::now()->format('Y');
    $Results=Result::class;
    
    $dashboard=$this;
    
    $yearResults=array();
    $periods=array();
    $periods['today']=Carbon::now()->format('Y-m-d');
    $periods['yesterday']=Carbon::now()->subDay()->format('Y-m-d');
    $periods['monday']=Carbon::now()->startOfWeek()->format('Y-m-d');
    $periods['first_day_month']=Carbon::now()->startOfMonth()->format('Y-m-d');
    $periods['year']=Carbon::now()->format('Y');
    $periods['month']=Carbon::now()->format('m');
    $periods['first_day_year']=Carbon::now()->startOfYear()->format('Y-m-d');
    $periods['last_30']=Carbon::now()->subMonth()->format('Y-m-d');
    $periods['last_60']=Carbon::now()->subMonth(2)->format('Y-m-d');
    $managers=User::whereHas('tenders')->get();
        
    return view('site::admin.dashboard.tenders', compact('managers','periods'));
    
    
    }
    
    
    public function ascCsc(Request $request)
	{
    $years = [2018,Carbon::now()->addYears(1)->format('Y'),Carbon::now()->format('Y')];
    $today = Carbon::now()->format('Y-m-d');
    $request->input('year') ? $year=$request->input('year') : $year=Carbon::now()->format('Y');
    $Results=Result::class;
    
    $dashboard=$this;
    
    $yearResults=array();
    $periods=array();
    
    $periods['today']=Carbon::now()->format('Y-m-d');
    $periods['yesterday']=Carbon::now()->subDay()->format('Y-m-d');
    $periods['monday']=Carbon::now()->startOfWeek()->format('Y-m-d');
    $periods['first_day_month']=Carbon::now()->startOfMonth()->format('Y-m-d');
    $periods['first_day_year']=Carbon::now()->startOfYear()->format('Y-m-d');
    $periods['last_7']=Carbon::now()->subWeek()->format('Y-m-d');
    $periods['last_30']=Carbon::now()->subMonth()->format('Y-m-d');
    $periods['last_60']=Carbon::now()->subMonth(2)->format('Y-m-d');
   
    $ferroliManagers=User::whereIn('email', Region::where('country_id','643')->pluck('notification_address'));
    
    $ascs_year_model=Result::where('type_id','asc_count')->where('date_actual',$year .'-01-01')->first();
    $ascs_year=!empty($ascs_year_model) ? $ascs_year_model->value_int : 0;
    
    
    $cscdistr=User::whereHas('roles', function ($query){$query->whereIn('name', ['distr','gendistr','csc']);})->where('active','1')->get();
    $ascs=User::whereHas('roles', function ($query){$query->whereIn('name', ['asc','csc']);})->where('active','1')->get();
    $cscs=User::whereHas('roles', function ($query){$query->whereIn('name', ['csc']);})->where('active','1')->get();
    $engeneers=User::whereHas('roles', function ($query){$query->whereIn('name', ['service_fl']);})->where('active','1')->get();
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
                                                                
   // dd($ascs_with_contract->where('region_id','RU-MOW'));
    
    
    $authorizations_wait=Authorization::where('status_id','1')->where('role_id','3')->get();
    
    $repairs=Repair::where('created_at','>=',$year .'.01.01')->where('created_at','<=',$year .'.12.31')->get();
    $repairs_approved=Repair::where('created_at','>=',$year .'.01.01')->where('created_at','<=',$year .'.12.31')->where('status_id','5')->get();
    $repairs_declined=Repair::where('created_at','>=',$year .'.01.01')->where('created_at','<=',$year .'.12.31')->where('status_id','4')->get();
    $repairs_deleted=Repair::where('created_at','>=',$year .'.01.01')->where('created_at','<=',$year .'.12.31')->where('status_id','6')->get();
    $repairs_wait=Repair::where('created_at','>=',$year .'.01.01')->where('created_at','<=',$year .'.12.31')->whereIn('status_id',['1','2','3']);
    $repairs_paid=Repair::where('created_at','>=',$year .'.01.01')->where('created_at','<=',$year .'.12.31')
        ->where('status_id','5')->whereHas('act', function ($query) {
        $query->where('paid','1');
    });
    
    
    $repairs_money=$repairs_paid->get()->sum('total_difficulty_cost')+$repairs_paid->get()->sum('total_distance_cost')+$repairs_paid->get()->sum('total_cost_parts');
    $storehouses=Storehouse::whereIn('user_id',$cscdistr->pluck('id'))->where('enabled',1)->get();
    $storehouses_csc=Storehouse::whereIn('user_id',$cscs->pluck('id'))->where('enabled',1)->get();
    $events_1q=Event::whereIn('status_id',['2','3'])->where('date_from','>=',$year .'.01.01')->where('date_from','<=',$year .'.03.31')->get();
    $events_2q=Event::whereIn('status_id',['2','3'])->where('date_from','>=',$year .'.04.01')->where('date_from','<=',$year .'.06.30')->get();
    $events_3q=Event::whereIn('status_id',['2','3'])->where('date_from','>=',$year .'.07.01')->where('date_from','<=',$year .'.09.30')->get();
    $events_4q=Event::whereIn('status_id',['2','3'])->where('date_from','>=',$year .'.09.01')->where('date_from','<=',$year .'.12.31')->get();
    
    $asc_not_discount = User::whereHas('roles', function ($query){$query->whereIn('name', ['asc','csc']);})->where('active','1')->
        whereDoesntHave('prices', function ($q) {
        $q->where('product_type_id', 8)->whereIn('price_type_id',['7fb003f6-aca8-11e8-80cc-85ebbdeccdc7',
                                                                  '7fb003f7-aca8-11e8-80cc-85ebbdeccdc7',
                                                                  '7fb003f5-aca8-11e8-80cc-85ebbdeccdc7',
                                                                  'cb874733-faf8-11e9-80d5-8912dc3f4be4']);
        })->get();
        
    $asc_no_show = $ascs->filter(function($model) {
        return $model->show_map_service != 1;
    });
    
    $yearResults['count_asc']=$ascs->count();         
    $yearResults['ascs_year']=$ascs_year;         
    $yearResults['count_ascs_with_contract']=$ascs_with_contract->count();         
    $yearResults['count_engeneers']=$engeneers->count();         
    $yearResults['count_engeneers_attached']=$engeneers_attached->count();         
    $yearResults['count_engeneers_cert']=$engeneers_cert->count();         
    $yearResults['count_authorizations_wait']=$authorizations_wait->count();         
    $yearResults['repairs']=$repairs->count();         
    $yearResults['repairs_approved']=$repairs_approved->count();         
    $yearResults['repairs_declined']=$repairs_declined->count();         
    $yearResults['repairs_deleted']=$repairs_deleted->count();         
    $yearResults['repairs_wait']=$repairs_wait->count();         
    $yearResults['repairs_paid']=$repairs_paid->count();         
    $yearResults['repairs_money']=round($repairs_money,0);         
    $yearResults['storehouses']=$storehouses->count();                
    $yearResults['events_1q']=$events_1q->count();         
    $yearResults['events_2q']=$events_2q->count();         
    $yearResults['events_3q']=$events_3q->count();         
    $yearResults['events_4q']=$events_4q->count();         
    
     
    if ($request->has('excel')) {
        (new AscYearExcel())->setYear($year)->setPeriods($periods)->render();
        }
    
    return view('site::admin.dashboard.asc_csc', compact('dashboard','Results','years','year',
                                                            'yearResults','storehouses','storehouses_csc',
                                                            'periods','cscs','ferroliManagers',
                                                            'ascs_with_contract','asc_not_discount','asc_no_show'));
    
    }
    
    public function resultSumAllRegionsTypeToDate($type_id, $date)
    {
        $res_val = 0;
        
        foreach(Region::where('country_id', '643')->get() as $region) {
            $result=Result::where('type_id',$type_id)->where('date_actual',$date)->where('object_id',$region->id)->where('objectable','region')->first();
            if(!empty($result)){ $res_val=$res_val+$result->value_int;}
        
        }
        return $res_val;
    }
 
    
   
    public function repairsYear($year, $status_id = null, $paid = null) {
        
        $repairs=Repair::where('created_at','>=',$year .'.01.01')->where('created_at','<=',$year .'.12.31');
        if ($paid) {
            $repairs=$repairs->whereHas('act', function ($query) {$query->where('paid','1');});
        }
        if ($status_id) {
            if(is_array($status_id)){
                $repairs=$repairs->whereIn('status_id',$status_id);
            } else { 
                $repairs=$repairs->where('status_id',$status_id);
            }
        } 
        
        return $repairs;
        
    }
   
    public function repairsToDate($year, $date_to, $status_id = null, $paid = null) {
        
        $repairs=Repair::where('created_at','>=',$year .'.01.01')->where('created_at','<=',Carbon::parse($date_to)->format('Y.m.d'));
        
        if ($paid) {
            $repairs=$repairs->whereHas('act', function ($query) {$query->where('paid','1');});
        }
        if ($status_id) {
            if(is_array($status_id)){
                $repairs=$repairs->whereIn('status_id',$status_id);
            } else { 
                $repairs=$repairs->where('status_id',$status_id);
            }
        } 
        
        return $repairs;
        
    }
    public function repairsPaidSummToDate($year, $date_to) {
        
        $repairs_paid=Repair::where('created_at','>=',$year .'.01.01')->where('created_at','<=',Carbon::parse($date_to)->format('Y.m.d'))
                    ->whereHas('act', function ($query) {$query->where('paid','1');});
        
        
        
        return $repairs_paid->get()->sum('total_difficulty_cost')+$repairs_paid->get()->sum('total_distance_cost')+$repairs_paid->get()->sum('total_cost_parts');
        
    }
}