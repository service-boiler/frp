<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use ServiceBoiler\Prf\Site\Facades\Site;


class Mission extends Model
{

	/**
	 * @var string
	 */
	protected $table = 'missions';

	/**
	 * @var array
	 */
	protected $fillable = [
		'user_id', 
		'division_id', 
		'project_id', 
		'region_id', 
		'budget', 
		'budget_currency_id', 
		'status_id', 
		'created_by_user_id',
        'locality', 
		'target', 
		'result', 
		'date_from', 
		'date_to', 
		'comments',
        
	];

	/**
	 * @var array
	 */
	protected $casts = [
        'user_id' => 'integer', 
		'division_id' => 'integer', 
		'project_id' => 'integer', 
		'region_id' => 'string', 
		'budget' => 'integer', 
		'budget_currency_id' => 'integer', 
		'status_id' => 'integer', 
		'created_by_user_id' => 'integer',
        'locality' => 'string', 
		'target' => 'string', 
		'result' => 'string', 
		'date_from' => 'date:Y-m-d', 
		'date_to' => 'date:Y-m-d', 
		'comments' => 'string',
        
	];

	/**
	 * @var array
	 */
	protected $dates = [
		'date_from',
		'date_to',
	];


	public function setDateFromAttribute($value)
	{
		$this->attributes['date_from'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
	}

	public function setDateToAttribute($value)
	{
		$this->attributes['date_to'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
	}
	
	public function files()
	{
		return $this->morphMany(File::class, 'fileable');
	}

	public function detachFiles()
	{
		foreach ($this->files as $file) {
			$file->fileable_id = null;
			$file->fileable_type = null;
			$file->save();
		}
	}

    public function missionUsers()
    {
        return $this->hasMany(MissionUser::class);
    }
	public function creator()
	{
		return $this->belongsTo(User::class, 'created_by_user_id');
	}

    
	public function division()
	{
		return $this->belongsTo(CrmDivision::class);
	}

    
	public function project()
	{
		return $this->belongsTo(CrmProject::class);
	}

	public function budgetCurrency()
	{
		return $this->belongsTo(Currency::class);
	}
    
	public function getBudgetCurrencyEurRateAttribute()
	{ 
		return round(CurrencyArchive::where('currency_id','978')->where('date', '<=',$this->getAttribute('date_from')->format('Y-m-d'))->orderByDesc('date')->first()->rates,2) ;
        
	}
	
    public function getBudgetRubAttribute()
	{ 
		if($this->budgetCurrency->id=='978') {
            return $this->budget*$this->budget_currency_eur_rate;
        } else {
            return $this->budget;
        }
        
	}
    public function getBudgetEurAttribute()
	{ 
		if($this->budgetCurrency->id!='978') {
            return $this->budget/$this->budget_currency_eur_rate;
        } else {
            return $this->budget;
        }
        
	}
	
    public function status()
	{
		return $this->belongsTo(MissionStatus::class);
	}
    public function region()
	{
		return $this->belongsTo(Region::class);
	}

	
	public function users()
	{
        
        return $this->belongsToMany(
            User::class,
            'mission_users',
            'mission_id',
            'user_id'
        )->withPivot('id','main');
	}

	public function statuses()
	{   $user=Auth::user();
        
        if($user->admin == 1 || $user->hasRole('supervisor')) {
        $user_status = 'head';
        } elseif($user->id == $this->created_by_user_id || (!empty($this->users) && in_array($user->id, $this->users()->pluck('users.id')->toArray() ))) {
            $user_status = 'user';
        } else {
            $user_status = 'viewer';
        }
        
		return MissionStatus::query()->whereIn('id', config('site.mission_status_transition.' .$user_status . '.' . $this->getAttribute('status_id'), []))->orderBy('sort_order');
	}
    
   
}
