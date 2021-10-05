<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackStatus extends Model
{

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'status_id');
    }

}
