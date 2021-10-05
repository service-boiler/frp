<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Events\UserFlRoleRequestCreateEvent;
use ServiceBoiler\Prf\Site\Mail\UserFlRoleRequest\AdminUserFlRoleRequestCreateEmail;
use ServiceBoiler\Prf\Site\Mail\UserFlRoleRequest\UserUserFlRoleRequestCreateEmail;

class UserFlRoleRequestListener
{

    /**
     * @param UserFlRoleRequestCreateEvent $event
     */
    public function onUserFlRoleRequestCreate(UserFlRoleRequestCreateEvent $event)
    {
            $parent_email = !empty($event->userFlRoleRequest->user->userRelationParents) ? $event->userFlRoleRequest->user->userRelationParents->sortByDesc('created_at')->first()->parent->email : env('MAIL_SERVICE_ADDRESS');
        
            $manager_email_address = $event->userFlRoleRequest->user->region->notification_address;

            // Отправка admin_siteистратору и менеджеру уведомления о новой заявке на связь
            
            // if(!empty($manager_email_address) &  $manager_email_address != env('MAIL_NEW_USER_ADDRESS')){
               // Mail::to([$manager_email_address , env('MAIL_NEW_USER_ADDRESS') , env('MAIL_SERVICE_ADDRESS'), env('MAIL_DEVEL_ADDRESS')])->send(new AdminUserFlRoleRequestCreateEmail($event->userFlRoleRequest));
              // }
              // else {
              // Mail::to([env('MAIL_NEW_USER_ADDRESS'),env('MAIL_SERVICE_ADDRESS'), env('MAIL_DEVEL_ADDRESS')])->send(new AdminUserFlRoleRequestCreateEmail($event->userFlRoleRequest));
              // }
		  
            Mail::to([$parent_email, env('MAIL_DEVEL_ADDRESS')])->send(new UserUserFlRoleRequestCreateEmail($event->userFlRoleRequest));
        

    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {

        $events->listen(
            UserFlRoleRequestCreateEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\UserFlRoleRequestListener@onUserFlRoleRequestCreate'
        );


    }
}