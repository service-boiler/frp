<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class QueueSms extends Model
{
    /**
     * @var string
     */
    protected $table = 'queue_sms';

    protected $fillable = ['phone', 'text', 'sended', 'error','error_text','bulk','bulk_id','creator_id'];

   

}
