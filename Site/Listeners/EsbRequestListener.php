<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Events\EsbRequestCreateEvent;
use ServiceBoiler\Prf\Site\Events\EsbServiceChangeEvent;
use ServiceBoiler\Prf\Site\Events\EsbServiceDeleteEvent;
use ServiceBoiler\Prf\Site\Events\EsbVisitCreateEvent;
use ServiceBoiler\Prf\Site\Events\EsbProductLaunchCreateEvent;
use ServiceBoiler\Prf\Site\Events\EsbVisitStatusEvent;
use ServiceBoiler\Prf\Site\Mail\Esb\EsbRequestEmail;
use ServiceBoiler\Prf\Site\Mail\Esb\EsbProductLaunchEmail;

use ServiceBoiler\Prf\Site\Mail\Esb\EsbServiceChangeEmail;
use ServiceBoiler\Prf\Site\Mail\Esb\EsbServiceDeleteEmail;
use ServiceBoiler\Prf\Site\Mail\Esb\EsbVisitEmail;
use ServiceBoiler\Prf\Site\Services\Sms;

class EsbRequestListener
{

    /*
     * За 1 месяц и за 1 неделю до планового ежегодного ТО отправляется СМС и email клиенту с напоминанием,
        сервису отправляется email о приближении срока ТО.
        При добавлении выезда к клиенту в "запланированные выезды" отправляется СМС и email с просьбой подтвердить время и дату выезда.
        Сервис может подтвердить время и дату за клиента (например, по телефону ранее согласовали), клиенту уйдет об этом сообщение.

        При добавлении пользователем:
        + заявки в сервис,
        + "черновика" акта о вводе в эксплуатацию,
        + при выборе сервисного центра в "свои",
        сообщение уходит на email и в личный кабинет сервиса.

        При добавлении сервисом:
        - акта о пуско-наладочных работах,
        - акта о платном ремонте,
        - акта о гарантийном ремонте,
        сообщение уходит на email и в личный кабинет пользователя.

     */


    public function onEsbUserLaunchCreate(EsbProductLaunchCreateEvent $event)
    {
        $launch=$event->esbProductLaunch;

        if($launch->created_by_consumer && $launch->service_id) {
            $text="Пользователем добавлены данные о пусконаладочных работах.";
            $launch->messages()->save($launch->esbUser->outbox()->create(['text'=>$text, 'receiver_id'=>$launch->service_id, 'personal'=>'0']));

            Mail::to(env('MAIL_TO_ADDRESS'))->send(new EsbProductLaunchEmail($launch, 'site::esb_product_launch.email_create_service'));
            Mail::to($launch->service->email)->send(new EsbProductLaunchEmail($launch, 'site::esb_product_launch.email_create_service'));
        } elseif ($launch->service_id)
        {
            $text="Сервисным центром добавлены данные о пусконаладочных работах.";
            $subject="Добавлен новый акт о пуско-наладочных работах. ";
            $launch->messages()->save($launch->service->outbox()->create(['text'=>$text, 'receiver_id'=>$launch->esb_user_id, 'personal'=>'0']));

            Mail::to(env('MAIL_TO_ADDRESS'))->send(new EsbProductLaunchEmail($launch, 'site::esb_product_launch.email_create_service'));
            Mail::to($launch->esbUser->email)->send(new EsbProductLaunchEmail($launch, 'site::esb_product_launch.email_create_user', $subject));
        }




    }

    public function onEsbServiceChange(EsbServiceChangeEvent $event)
    {
            $esbUser=$event->esbUser;
            $service=$event->service;


            $text='Пользователем выбран Ваш АСЦ. <a href="/esb-users">/esb-users</a>';
            $message=$esbUser->outbox()->create(['text'=>$text, 'receiver_id'=>$service->id, 'personal'=>'0']);

            Mail::to(env('MAIL_TO_ADDRESS'))->send(new EsbServiceChangeEmail($esbUser, 'site::esb_user.email_change_service'));
            Mail::to($service->email)->send(new EsbServiceChangeEmail($esbUser, 'site::esb_user.email_change_service'));


    }

    public function onEsbServiceDelete(EsbServiceDeleteEvent $event)
    {
            $esbUser=$event->esbUser;
            $service=$event->service;


            $text='Пользователь открепился от Вашего АСЦ.';
            $message=$esbUser->outbox()->create(['text'=>$text, 'receiver_id'=>$service->id, 'personal'=>'0']);

            Mail::to(env('MAIL_TO_ADDRESS'))->send(new EsbServiceDeleteEmail($esbUser, 'site::esb_user.email_delete_service'));
            Mail::to($service->email)->send(new EsbServiceDeleteEmail($esbUser, 'site::esb_user.email_delete_service'));


    }
    public function onEsbRequestCreate(EsbRequestCreateEvent $event)
    {
        Mail::to(env('MAIL_TO_ADDRESS'))->send(new EsbRequestEmail($event->esbRequest, 'site::esb_request.email_create_service'));
        Mail::to($event->esbRequest->service->email)->send(new EsbRequestEmail($event->esbRequest, 'site::esb_request.email_create_service'));

    }
    
    public function onEsbVisitCreate(EsbVisitCreateEvent $event)
    {
        $visit=$event->esbUserVisit;

        $response = (new Sms())->sendSms('SendMessage',['phone'=>$visit->esbUser->phone,
        'message'=>'Запланировано техническое обслуживание котла ' .trans('site::date.week.' .$visit->date_planned->dayOfWeek) .' ' .$visit->date_planned->format('d.m H:i') .
                    '. Перейдите в свой личный кабинет на сайте market.ferroli.ru']);

        $text='Сервисным центром запланировано техническое обслуживание котла ' .trans('site::date.week.' .$visit->date_planned->dayOfWeek) .' ' .$visit->date_planned->format('d.m H:i');
        $subject="Запланировано техническое обслуживание котла. ";
        $visit->messages()->save($visit->service->outbox()->create(['text'=>$text, 'receiver_id'=>$visit->client_user_id, 'personal'=>'0']));

       Mail::to($visit->esbUser->email)->send(new EsbVisitEmail($visit, 'site::esb_user_visit.email_create_user', $subject));
       Mail::to(env('MAIL_TO_ADDRESS'))->send(new EsbVisitEmail($visit, 'site::esb_user_visit.email_create_user', $subject));


    }

    public function onEsbVisitStatus(EsbVisitStatusEvent $event)
    {
        $visit=$event->esbUserVisit;

        $response = (new Sms())->sendSms('SendMessage',['phone'=>$visit->esbUser->phone,
        'message'=>'Изменен статус запланированного визита: '  .$visit->status->name]);

        $text='Изменен статус запланированного визита: '  .$visit->status->name;
        $subject='Изменен статус запланированного визита: '  .$visit->status->name;
        $visit->messages()->save($visit->service->outbox()->create(['text'=>$text, 'receiver_id'=>$visit->client_user_id, 'personal'=>'0']));

       Mail::to($visit->esbUser->email)->send(new EsbVisitEmail($visit, 'site::esb_user_visit.email_status_user', $subject));
       Mail::to($visit->service->email)->send(new EsbVisitEmail($visit, 'site::esb_user_visit.email_status_service', $subject));
       Mail::to(env('MAIL_TO_ADDRESS'))->send(new EsbVisitEmail($visit, 'site::esb_user_visit.email_status_user', $subject));


    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        
        $events->listen(
            EsbRequestCreateEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\EsbRequestListener@onEsbRequestCreate'
        );

        $events->listen(
            EsbVisitCreateEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\EsbRequestListener@onEsbVisitCreate'
        );

        $events->listen(
            EsbVisitStatusEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\EsbRequestListener@onEsbVisitStatus'
        );

        $events->listen(
            EsbProductLaunchCreateEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\EsbRequestListener@onEsbUserLaunchCreate'
        );

        $events->listen(
            EsbServiceChangeEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\EsbRequestListener@onEsbServiceChange'
        );

        $events->listen(
            EsbServiceDeleteEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\EsbRequestListener@onEsbServiceDelete'
        );


    }
}