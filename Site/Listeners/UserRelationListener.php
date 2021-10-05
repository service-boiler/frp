<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Events\UserRelationCreateEvent;
use ServiceBoiler\Prf\Site\Mail\UserRelation\AdminUserRelationCreateEmail;
use ServiceBoiler\Prf\Site\Mail\UserRelation\UserUserRelationCreateEmail;

class UserRelationListener
{

    /**
     * @param UserRelationCreateEvent $event
     */
    public function onUserRelationCreate(UserRelationCreateEvent $event)
    {

        if ($event->userRelation->parent->email) {

            // Отправка СЦ уведомления о новой заявке на связь
            Mail::to($event->userRelation->parent->email)->send(new UserUserRelationCreateEmail($event->userRelation));
        
            $manager_email_address = $event->userRelation->parent->region->notification_address;

            // Отправка admin_siteистратору и менеджеру уведомления о новой заявке на связь
            
            if(!empty($manager_email_address) &  $manager_email_address != env('MAIL_NEW_USER_ADDRESS')){
               Mail::to([$manager_email_address , env('MAIL_NEW_USER_ADDRESS') , env('MAIL_SERVICE_ADDRESS')])->send(new AdminUserRelationCreateEmail($event->userRelation));
              }
              else {
              Mail::to([env('MAIL_NEW_USER_ADDRESS'),env('MAIL_SERVICE_ADDRESS')])->send(new AdminUserRelationCreateEmail($event->userRelation));
              }
		  
        
        }

    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {

        $events->listen(
            UserRelationCreateEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\UserRelationListener@onUserRelationCreate'
        );


    }
}