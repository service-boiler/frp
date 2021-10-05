<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Events\UserScheduleEvent;
use ServiceBoiler\Prf\Site\Mail\Admin\User\UserRegisteredEmail;
use ServiceBoiler\Prf\Site\Mail\User\UserConfirmationEmail;


class UserListener
{

    /**
     * @param Login $event
     */
    public function onUserLogin(Login $event)
    {
        $event->user->logged_at = Carbon::now();
        $event->user->save();
    }

    /**
     * @param UserScheduleEvent $event
     */
    public function onUserAuthorized(UserScheduleEvent $event)
    {
        $event->user->schedules()->create([
            'action_id' => 1,
            'url'       => preg_split("/:\/\//", route('api.users.show', $event->user))[1]
        ]);
        //Schedule::create();
    }

    /**
     * События при регистрации сервисного центра на сайте
     * @param UserScheduleEvent $event
     */
    public function onUserRegistered(Registered $event)
    {

        // Отправка сервисному центру письма о подтверждении E-mail
        if(!empty($event->user->email)) {
                Mail::to($event->user->getEmailForPasswordReset())->send(new UserConfirmationEmail($event->user));
            
        }
        
        if(!empty($event->user->region->notification_address) &  $event->user->region->notification_address != env('MAIL_NEW_USER_ADDRESS')){
            Mail::to([$event->user->region->notification_address , env('MAIL_NEW_USER_ADDRESS') , env('MAIL_SERVICE_ADDRESS')])->send(new UserRegisteredEmail($event->user));
		  }
		  else {
		  Mail::to([env('MAIL_NEW_USER_ADDRESS'),env('MAIL_SERVICE_ADDRESS')])->send(new UserRegisteredEmail($event->user));
		  }
		  
		  

    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {

        $events->listen(
            Login::class,
            'ServiceBoiler\Prf\Site\Listeners\UserListener@onUserLogin'
        );
        $events->listen(
            Registered::class,
            'ServiceBoiler\Prf\Site\Listeners\UserListener@onUserRegistered'
        );
        $events->listen(
            UserScheduleEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\UserListener@onUserAuthorized'
        );
    }
}