<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewStatus extends Model
{

	/**
	 * @var string
	 */
	protected $table = 'review_statuses';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'status_id');
    }

}
