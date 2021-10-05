<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Events\RepairCreateEvent;
use ServiceBoiler\Prf\Site\Events\RepairEditEvent;
use ServiceBoiler\Prf\Site\Events\RepairStatusChangeEvent;
use ServiceBoiler\Prf\Site\Mail\Admin\Repair\RepairCreateEmail;
use ServiceBoiler\Prf\Site\Mail\Admin\Repair\RepairEditEmail;
use ServiceBoiler\Prf\Site\Mail\User\Repair\RepairStatusChangeEmail;

class RepairListener
{

    /**
     * Обработчик события:
     * Создание отчета по ремонту
     *
     * @param RepairCreateEvent $event
     */
    public function onRepairCreate(RepairCreateEvent $event)
    {
        // Отправка admin_siteистратору письма при создании нового отчета по ремонту
        Mail::to(env('MAIL_TO_ADDRESS'))->send(new RepairCreateEmail($event->repair));
    }

    /**
     * Обработчик события:
     * Исправление отчета по ремонту
     *
     * @param RepairEditEvent $event
     */
    public function onRepairEdit(RepairEditEvent $event)
    {
        // Отправка admin_siteистратору письма при исправлении отчета по ремонту
        Mail::to(env('MAIL_TO_ADDRESS'))->send(new RepairEditEmail($event->repair));
    }

    /**
     * Обработчик события:
     * Смена admin_siteистратором статуса отчета по ремонту
     *
     * @param RepairStatusChangeEvent $event
     */
    public function onRepairStatus(RepairStatusChangeEvent $event)
    {
        // Отправка пользователю письма при смене статуса отчета по ремонту
        Mail::to($event->repair->user->email)->send(new RepairStatusChangeEmail($event->repair));
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            RepairCreateEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\RepairListener@onRepairCreate'
        );

        $events->listen(
            RepairEditEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\RepairListener@onRepairEdit'
        );

        $events->listen(
            RepairStatusChangeEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\RepairListener@onRepairStatus'
        );
    }
}