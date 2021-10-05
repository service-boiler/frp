<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use ServiceBoiler\Prf\Site\Http\Requests\Admin\UserRequest;


use ServiceBoiler\Prf\Site\Models\Act;
use ServiceBoiler\Prf\Site\Models\Event;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Models\Region;
use ServiceBoiler\Prf\Site\Models\RegionItalyDistrict;
use ServiceBoiler\Prf\Site\Models\Repair;
use ServiceBoiler\Prf\Site\Models\Result;
use ServiceBoiler\Prf\Site\Models\Storehouse;

class ResultsController extends Controller
{

    use AuthorizesRequests;
    
    
    public function today()
    {
      $today = Carbon::now()->format('Y-m-d');
      $ascs=User::whereHas('roles', function ($query){$query->whereIn('name', ['asc','csc']);})->where('active','1')->get();
      $engeneers=User::whereHas('roles', function ($query){$query->whereIn('name', ['service_fl']);})->where('active','1')->get();
      $storehouses=Storehouse::whereIn('user_id',$ascs->pluck('id'))->where('enabled',1)->get();
         
       $result = Result::updateOrCreate(['type_id'=>'asc_count','date_actual'=>$today,'period_type'=>'all'],['value_int'=>$ascs->count()]);
      
      
      foreach(Region::where('country_id',643)->get() as $region){
         $result = Result::updateOrCreate(['type_id'=>'reg_asc_count','object_id'=>$region->id,'objectable'=>'region','date_actual'=>$today,'period_type'=>'all'],
             ['value_int'=>$region->count_asc_today]);
        $result = Result::updateOrCreate(['type_id'=>'reg_asc_contract_count','object_id'=>$region->id,'objectable'=>'region','date_actual'=>$today,'period_type'=>'all'],
            ['value_int'=>$region->count_asc_contract_today]);
        $result = Result::updateOrCreate(['type_id'=>'reg_engeneers_count','object_id'=>$region->id,'objectable'=>'region','date_actual'=>$today,'period_type'=>'all'],
            ['value_int'=>$region->count_engeneers_today]);
        $result = Result::updateOrCreate(['type_id'=>'reg_engeneers_attached_count','object_id'=>$region->id,'objectable'=>'region','date_actual'=>$today,'period_type'=>'all'],
            ['value_int'=>$region->count_engeneers_attached_today]);
        $result = Result::updateOrCreate(['type_id'=>'reg_engeneers_cert_count','object_id'=>$region->id,'objectable'=>'region','date_actual'=>$today,'period_type'=>'all'],
            ['value_int'=>$region->count_engeneers_cert_today]);
        
        
      
      }
      
      
      $result = Result::updateOrCreate(['type_id'=>'engeneer_service_fl_count','date_actual'=>$today,'period_type'=>'all'],['value_int'=>$engeneers->count()]);
      $result = Result::updateOrCreate(['type_id'=>'storehouses_all_count','date_actual'=>$today,'period_type'=>'all'],['value_int'=>$storehouses->count()]);
      $result = Result::updateOrCreate(['type_id'=>'storehouses_all_summ','date_actual'=>$today,'period_type'=>'all'],['value_int'=>round($storehouses->sum('total_cost_products'),0)]);
      $result = Result::updateOrCreate(['type_id'=>'storehouses_zip_summ','date_actual'=>$today,'period_type'=>'all'],['value_int'=>round($storehouses->sum('total_cost_products_zip'),0)]);
      $result = Result::updateOrCreate(['type_id'=>'storehouses_equipment_summ','date_actual'=>$today,'period_type'=>'all'],['value_int'=>round($storehouses->sum('total_cost_products_equipment'),0)]);
      $result = Result::updateOrCreate(['type_id'=>'storehouses_accessories_summ','date_actual'=>$today,'period_type'=>'all'],['value_int'=>round($storehouses->sum('total_cost_products_accessories'),0)]);
      $result = Result::updateOrCreate(['type_id'=>'storehouses_other_summ','date_actual'=>$today,'period_type'=>'all'],['value_int'=>round($storehouses->sum('total_cost_products_other'),0)]);
      
      foreach($storehouses as $storehouse) {
        $result = Result::updateOrCreate(['type_id'=>'storehouse_all_summ','object_id'=>$storehouse->id,'objectable'=>'storehouse','date_actual'=>$today,'period_type'=>'all'],
                                            ['value_int'=>round($storehouse->total_cost_products,0)]);
        $result = Result::updateOrCreate(['type_id'=>'storehouse_zip_summ','object_id'=>$storehouse->id,'objectable'=>'storehouse','date_actual'=>$today,'period_type'=>'all'],
                                            ['value_int'=>round($storehouse->total_cost_products_zip,0)]);
        $result = Result::updateOrCreate(['type_id'=>'storehouse_equipment_summ','object_id'=>$storehouse->id,'objectable'=>'storehouse','date_actual'=>$today,'period_type'=>'all'],
                                            ['value_int'=>round($storehouse->total_cost_products_equipment,0)]);
        $result = Result::updateOrCreate(['type_id'=>'storehouse_accessories_summ','object_id'=>$storehouse->id,'objectable'=>'storehouse','date_actual'=>$today,'period_type'=>'all'],
                                            ['value_int'=>round($storehouse->total_cost_products_accessories,0)]);
        $result = Result::updateOrCreate(['type_id'=>'storehouse_other_summ','object_id'=>$storehouse->id,'objectable'=>'storehouse','date_actual'=>$today,'period_type'=>'all'],
                                            ['value_int'=>round($storehouse->total_cost_products_other,0)]);
      }
      
      
      
        if (!Auth::guest() && Auth::user()->admin == 1) {
            return redirect()->route('home')->with('success', 'Итоги пересчитаны');
        }

        return null;

    }
}