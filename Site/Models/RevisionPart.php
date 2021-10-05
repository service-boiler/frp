<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;


class RevisionPart extends Model
{

	/**
	 * @var string
	 */
	protected $table = 'revision_parts';

	/**
	 * @var array
	 */
	protected $fillable = [
		'enabled', 
		'public', 
		'part_new_id', 
		'part_old_id', 
		'part_new_sku', 
		'part_old_sku', 
		'interchange', 
        'text_object',
        'description',
        'comments',
        'date_change',
        'date_notice',
        'created_by_user_id',
        
	];

	/**
	 * @var array
	 */
	protected $casts = [

		'enabled' => 'integer',
		'public' => 'integer',
		'part_new_id' => 'string',
		'part_old_id' => 'string',
		'part_new_sku' => 'string', 
		'part_old_sku' => 'string',  
		'interchange' => 'integer', 
        'comments' => 'string',
		'date_change' => 'date:Y-m-d',
		'date_notice' => 'date:Y-m-d',
		'created_by_user_id' => 'integer',
	];

	/**
	 * @var array
	 */
	protected $dates = [
		'date_change',
		'date_notice',
	];


	public function setDateChangeAttribute($value)
	{
		$this->attributes['date_change'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
	}

	public function setDateNoticeAttribute($value)
	{
		$this->attributes['date_notice'] = $value ? Carbon::createFromFormat('d.m.Y', $value) : null;
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


	public function creator()
	{
		return $this->belongsTo(User::class);
	}

	
	public function partNew()
	{
		return $this->belongsTo(Product::class, 'part_new_id');
	}
	public function partOld()
	{
		return $this->belongsTo(Product::class, 'part_old_id');
	}

	
	public function products()
	{
        
        return $this->belongsToMany(
            Product::class,
            'revision_part_product_relations',
            'revision_part_id',
            'revision_product_id'
        )->withPivot('id','start_serial');
	}
	public function revisionPartProductRelations()
	{
        
       return $this->hasMany(RevisionPartProductRelation::class);
	}

    
   
}
