<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use ServiceBoiler\Prf\Site\Exceptions\Sms\SmsException;
use ServiceBoiler\Prf\Site\Facades\Site;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Services\Sms;

class SmsController extends Controller
{
    
    public function sendTest()
    {   
        $user=User::find(3);
        event(new Registered($user));
        
        //$response = (new Sms())->sendSms('SendMessage',['phone'=>'+79789848388','message'=>'45627']);
        
    }
    
    
}