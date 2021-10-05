<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{

	/**
	 * @var bool
	 */
	public $incrementing = false;
	/**
	 * @var array
	 */
	protected $fillable = ['id', 'name', 'created_at', 'type_id', 'organization', 'is_academy'];

	/**
	 * @var array
	 */
	protected $casts = [
		'id' => 'string',
		'name' => 'string',
		'organization' => 'string',
		'type_id' => 'integer',
		'engineer_id' => 'integer',
		'is_academy' => 'integer',
	];

	/**
	 * @var string
	 */
	protected $table = 'certificates';
    
    protected static function boot()
    {
        static::creating(function ($model) {
            $lastName = explode(' ',$model->user->name)[0];
            $firstName = explode(' ',$model->user->name)[1];
            
            if(!empty($model->user->enabledParents->first())) {
                if(!empty($model->user->enabledParents->first()->engineers->where('email',$model->user->email)->first())) {
                $model->user->enabledParents->first()->engineers->where('email',$model->user->email)->first()->update(['fl_user_id'=>$model->user->id]);
                } elseif(!empty($model->user->enabledParents->first()->engineers->
                                            filter(function ($engeneer) use ($lastName) {
                                                return false !== stristr($engeneer->name, $lastName);
                                            })->
                                            filter(function ($engeneer) use ($firstName) {
                                                return false !== stristr($engeneer->name, $firstName);
                                            })->first())) {
                $model->user->enabledParents->first()->engineers->
                                            filter(function ($engeneer) use ($lastName) {
                                                return false !== stristr($engeneer->name, $lastName);
                                            })->
                                            filter(function ($engeneer) use ($firstName) {
                                                return false !== stristr($engeneer->name, $firstName);
                                            })->first()->update(['fl_user_id'=>$model->user->id]);
                } else {
               
                $model->user->enabledParents->first()->engineers()
                                                        ->create(['user_id'=>$model->user->enabledParents->first()->id,
                                                                  'fl_user_id'=>$model->user->id, 
                                                                  'name'=>$model->user->name, 
                                                                  'email'=>$model->user->email, 
                                                                  'phone'=>$model->user->contacts->first()->phones->first()->number]);
                
                }
            }
            
        });

    }
	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function engineer()
	{
		return $this->belongsTo(Engineer::class);
	}
	public function user()
	{
		return $this->belongsTo(User::class);
	}


	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function type()
	{
		return $this->belongsTo(CertificateType::class);
	}

}
