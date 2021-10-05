<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use ServiceBoiler\Prf\Site\Concerns\Sortable;

class Review extends Model
{
    
    use Sortable;

    protected $table = 'reviews';
    
    /**
     * @var array
     */
    protected $fillable = [
        'message', 'email', 'phone', 'reviewable_id', 'reviewable_type', 'ip', 'user_id', 'status_id','name','rate'
    ];


    /**
     * Get all of the owning contactable models.
     */
    public function reviewable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(ReviewStatus::class);
    }
    
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
