<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EsbContract extends Model
{

    protected $table = 'esb_contracts';

    protected $fillable = [
        'service_id','service_contragent_id',
        'esb_client_id','client_user_id','esb_contragent_id',
        'date_from', 'date_to','date',
        'number', 'type_id','template_id','status_id'
    ];

    protected $casts = [
        'service_contragent_id' => 'integer',
        'esb_contragent_id' => 'integer',
        'date_from'         => 'date:Y-m-d',
        'date_to'           => 'date:Y-m-d',
        'number'            => 'string',
        'type_id'           => 'integer',
        'status_id'         => 'integer',
    ];

    protected $dates = [
        'date_from','date_to', 'date'
    ];

    protected static function boot()
    {
        // static::creating(function ($model) {
            // if ($model->getAttribute('automatic')) {
                // $max_id = DB::table('contracts')->max('id');
                // $model->number = $model->type->getAttribute('prefix') . (++$max_id);
            // }
        // });
    }

    /**
     * @param $value
     */
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
    }
    public function setDateToAttribute($value)
    {
        $this->attributes['date_to'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
    }
    public function setDateFromAttribute($value)
    {
        $this->attributes['date_from'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
    }

    public function getFieldValue(EsbContractField $field){
        if($field->calculated){
            switch ($field->id){
                case '6':{
                    if($contragent=$this->esbUserContragent){ return $contragent->name; } elseif($this->esbUser->last_name){
                        return $this->esbUser->last_name .' ' .$this->esbUser->first_name.' ' .$this->esbUser->middle_name;
                    } else { return $this->esbUser->name; }
                }
                case '7':{
                    if($contragent=$this->serviceContragent){ return $contragent->name; } elseif($this->service->last_name){
                        return $this->service->last_name .' ' .$this->service->first_name.' ' .$this->service->middle_name;
                    } else { return $this->service->name; }
                }
                case '8':{
                    if($number=$this->number){ return $number; } elseif($prefix=$this->template->prefix){
                        return $prefix .' ' .$this->id; } else { return $this->id; }
                }
                case '9':{ return $this->id; }
                case '10':{if($phone=$this->esbUser->phone){ return $phone; } else { return 'телефон_не_указан'; } }
                case '11':{if($email=$this->esbUser->email){  return $email; } else { return 'email_не_указан'; } }
                case '12':{if($contragent=$this->esbUserContragent){  return $contragent->inn; } else { return ''; } }
                case '13':{if($contragent=$this->esbUserContragent){  return $contragent->kpp; } else { return ''; } }
                case '14':{if($contragent=$this->esbUserContragent){  return $contragent->ogrn; } else { return ''; } }
                case '15':{if($contragent=$this->esbUserContragent){  return $contragent->bank; } else { return ''; } }
                case '16':{if($contragent=$this->esbUserContragent){  return $contragent->rs; } else { return ''; } }
                case '17':{if($contragent=$this->esbUserContragent){  return $contragent->ks; } else { return ''; } }
                case '18':{if($contragent=$this->esbUserContragent){  return $contragent->bik; } else { return ''; } }
                case '19':{if($contragent=$this->esbUserContragent){  return $contragent->addressLegal() ? $contragent->addressLegal()->full : ''; } else { return ''; } }
                case '20':{if($contragent=$this->serviceContragent){  return $contragent->addressLegal() ? $contragent->addressLegal()->full : ''; } else { return ''; } }
                case '25':{if($this->date){  return $this->date->format('d.m.Y'); }}
                case '5':{if($this->esbUserproducts()->exists()){
                    $products = $this->esbUserproducts->map(function ($item, $key) {
                        if($item->product_id){
                            return $item->product->name .' ' .$item->product_no_cat;
                        } else {
                            return $item->product_no_cat;
                        }
                    });
                    return $products->implode(', '); } else { return ''; } }
                default: {
                    return '{поле_'.$field->id .'_'.$field->name .'_не_вычислено}';
                }
            }
        } else {
            if($this->fields()->where('esb_contract_field_id',$field->id)->first()){
                return $this->fields()->where('esb_contract_field_id',$field->id)->first()->pivot->value;
            } else {return '';}

        }
    }

    public function service()
    {
        return $this->belongsTo(User::class,'service_id');
    }
    public function serviceContragent()
    {
        return $this->belongsTo(Contragent::class,'service_contragent_id');
    }

    public function esbUserContragent()
    {
        return $this->belongsTo(Contragent::class,'esb_contragent_id');
    }
    public function esbUser()
    {
        return $this->belongsTo(User::class,'client_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(EsbContractType::class, 'type_id');
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }
    public function template()
    {
        return $this->belongsTo(EsbContractTemplate::class,'template_id');
    }
    public function esbUserproducts()
    {
        return $this->hasMany(EsbUserProduct::class,'contract_id');
    }

    public function fields()
    {
        return $this->belongsToMany(EsbContractField::class,'esb_contract_field_relations', 'esb_contract_id','esb_contract_field_id')
            ->withPivot('value');
    }
}
