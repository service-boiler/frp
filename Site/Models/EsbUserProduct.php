<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EsbUserProduct extends Model
{
  
    protected $fillable = [
        'serial', 'enabled','product_id','product_no_cat','enabled','sale_id','launch_id','address_id','service_id','sale_comment','date_sale'
    ];

    protected $casts = [
        'enabled'      => 'integer',
        'serial'      => 'string',
    ];
    protected $dates = [
        'date_sale' 
    ];
    
    protected $table = 'esb_user_products';

    public function setDateSaleAttribute($value)
    {
        $this->attributes['date_sale'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
    }
    
    
    public function getNextMaintenanceAttribute () {
        $upteme_years=round($this->getUpTimeAttribute()/12)+1;
        
        if($this->maintenances()->exists() && $this->launches()->exists()){
            return $this->launches()->orderByDesc('date_launch')->first()->date_launch->addMonths(12*$upteme_years);
        } elseif($this->launches()->exists()) {
            return $this->launches()->first()->date_launch->addMonths(12);
        } elseif($this->maintenances()->exists()) {
            return $this->maintenances()->first()->date->addMonths(12);
        } else {
            return null;
        }
            
    
    }
    
    
    
    public function getAddressFiltredAttribute () {
        if($this->getPermissionServiceAttribute() || $this->esbUser->id == auth()->user()->id){
            return $this->address->full;
        } else {
            return $this->address->locality .' ' .$this->address->street;
        }
    
    }
    
    public function getPhoneFiltredAttribute () {
        if($this->getPermissionServiceAttribute() || $this->esbUser->id == auth()->user()->id){
            return $this->esbUser->phone;
        } else {
            return substr($this->esbUser->phone,0,3) .' *****' .substr($this->esbUser->phone,8,2);
        }
    
    }
    
    public function getPermissionServiceAttribute () {
        if($this->esbUser->esbRequests()->where('status_id','!=',7)->where('recipient_id',auth()->user()->id)->exists()
                     || $this->esbUser->esbServices()->wherePivot('enabled',1)->get()->contains(auth()->user()->id)
                     || auth()->user()->hasRole(config('site.supervisor_roles'),[])){
            return true;
        } else {
            return false;
        }
    
    }
    
    public function getUserFiltredAttribute () {
        if($this->getPermissionServiceAttribute() || $this->esbUser->id == auth()->user()->id){
            return $this->esbUser->name;
        } else {
            if($this->esbUser->first_name) {
                return $this->esbUser->first_name . ' ' . mb_substr($this->esbUser->last_name, 0, 1);
            } else {
                return mb_substr($this->esbUser->name, 0, 7) .'******';
            }
        }
    
    }
    
    public function getWarrantyAttribute () {
        $warranty=['type'=>'none','error'=>'']; 
        //dd($this->unbroken_maintenance);
        if( !$this->product){
            $warranty['type']='none';
            $warranty['error']='Причина: Нет данных о гарантии на оборудование';
            
        } elseif($this->up_time>60){
            $warranty['type']='none';
            $warranty['error']='Причина: Срок эксплуатации более 5 лет';
            
        } elseif(!$this->launches()->exists()){
            $warranty['type']='none';
            $warranty['error']='Причина: Нет данных о пусконаладочных работах';
            
        } elseif(!$this->launches()->where('approved',1)->exists()){
            $warranty['type']='none';
            $warranty['error']='Причина: Пусконаладочные работы не проверены авторизованным сервисным центром';
            
        } elseif( $this->next_maintenance < \Carbon\Carbon::now()->subMonth()  || !$this->unbroken_maintenance){
            $warranty['type']='none';
            $warranty['error']='Причина: Пропущено плановое техническое обслуживание';
            
        }  elseif( ($this->up_time > ($this->product->warranty_time_month ? $this->product->warranty_time_month : 24))
                    && !$this->product->warranty_extended){
            $warranty['type']='none';
            $warranty['error']='Причина: Срок гарантии истек';
            
        }  elseif( ($this->up_time > ($this->product->warranty_extended_time_month ? $this->product->warranty_extended_time_month : 60))
                    && ($this->up_time > (($this->product && $this->product->warranty_time_month) ? $this->product->warranty_time_month : 24))
                    ){
            $warranty['type']='none';
            $warranty['error']='Причина: Срок гарантии истек';
            
        }  elseif( ($this->up_time < ($this->product->warranty_time_month ? $this->product->warranty_time_month : 24))
                && $this->unbroken_maintenance
                && ($this->up_time < ($this->product->warranty_extended_time_month ? $this->product->warranty_extended_time_month : 60))
                && $this->product->warranty_extended
                ) {
            $warranty['type']='mainextended';
        }elseif($this->product->warranty && ($this->up_time < ($this->product->warranty_time_month ? $this->product->warranty_time_month : 24))) {
            $warranty['type']='main';
        }elseif($this->product->warranty_extended) {
            $warranty['type']='extended';
        }
        
       
        return $warranty;
    }
    
    public function getWarrantyMainBeforeAttribute () {
        return  $this->launch()->date_launch->addMonth($this->product->warranty_time_month ? $this->product->warranty_time_month : 24)->format('d.m.Y');
    }
    
    public function getWarrantyExtendedBeforeAttribute () {
        return  $this->launch()->date_launch->addMonth($this->product->warranty_time_extended_month ? $this->product->warranty_time_extended_month : 60)->format('d.m.Y');
    }
    
    public function getUpTimeAttribute() {
    
        return $this->launches()->exists() ?$this->launches()->first()->date_launch->diffInMonths(\Carbon\Carbon::now(),false) : null;
    
    }
    
    public function getUpTimeYearAttribute() {
    
        return $this->launches()->exists() ?$this->launches()->first()->date_launch->diffInYears(\Carbon\Carbon::now(),false)+1  : null;
    
    }
    
    public function getUnbrokenMaintenanceAttribute() {
        
        if(!$this->launches()->exists()){
            return false;
        }
        $launch_date=$this->launches()->first()->date_launch;
        
        if($this->launches()->exists() && $launch_date->diffInMonths(\Carbon\Carbon::now(),false) < 13) {
          return true;
        }
        
        if($this->launches()->exists() && $launch_date->diffInMonths(\Carbon\Carbon::now(),false) > 12 && !$this->maintenances()->exists()) {
          return false;
        }
        
        if($this->maintenances()->exists() && $launch_date->diffInMonths($this->maintenances()->orderBy('created_at')->first()->date,false) > 12) {
          return false;
        }
        
        $first_md=$this->maintenances()->orderBy('created_at')->first()->date;
        
        
        
        foreach($this->maintenances()->orderBy('created_at')->get() as $key=>$maintenance){
            $uptime=$launch_date->diffInMonths($maintenance->date,false);     
            
            if($uptime>($key+1)*12+1) {
              return false;
              break;
            } 
        
        }
        
        if($launch_date->addMonths(12*$launch_date->diffInYears(\Carbon\Carbon::now(),false))->addMonths(1) <  $this->maintenances()->orderByDesc('created_at')->first()->date){
              
             return false;
           
        }
        
        return true ;
    
    }
    
   
    
    public function esbUser()
	{
       return $this->hasOne(
			User::class,
            'id','user_id'
		);
	}
    
    public function esbClaim()
	{
       return $this->hasOne(
			EsbClaim::class,'esb_user_product_id','id'
		);
	}
    
    public function product()
	{
       return $this->hasOne(
			Product::class,
			'id','product_id'
		);
	}
    public function address()
	{
       return $this->hasOne(
			Address::class,
			'id','address_id'
		);
	}
    public function esbAdoContract()
	{
       return $this->belongsTo(
			EsbAdoContract::class,
			'id','esb_user_product_id'
		);
	}
    
    public function visits()
	{
       return $this->hasMany(
			EsbUserVisit::class,
			'esb_user_product_id','id'
		);
	}
    
    public function esbUserRequests()
	{
       return $this->hasMany(
			EsbUserRequest::class,
			'user_product_id','id'
		)->orderByDesc('created_at');
	}
    
    public function esbUserRequestActual()
	{
       return $this->esbUserRequests()->whereIn('status_id',[1,2,3])->whereNull('esb_user_visit_id')->first();
	}
    
    public function repairs()
	{
       return $this->hasMany(
			Repair::class,
			'esb_user_product_id','id'
		);
	}
    
    public function esbRepairs()
	{
       return $this->hasMany(
			EsbRepair::class,
			'esb_user_product_id','id'
		);
	}
    
    public function maintenances()
	{
       return $this->hasMany(
			EsbProductMaintenance::class,
			'esb_user_product_id','id'
		);
	}
    
    public function getLastMaintenanceAttribute()
	{
       return $this->maintenances()->orderByDesc('date')->first();
	}
    
    public function launches()
	{
       return $this->hasMany(
			EsbProductLaunch::class,
			'esb_user_product_id','id'
		)->whereActive('1')->orderByDesc('date_launch');
	}
    
    public function launch()
	{
       return $this->launches()->first();
	}
    
    public function esbService()
	{
       
       if($this->service_id){
        
		return	User::find($this->service_id);
       }
       if($this->maintenances()->exists()){
        return $this->maintenances()->first()->service;
       } else {
       return null;
       }
        
	}
    
    

}
