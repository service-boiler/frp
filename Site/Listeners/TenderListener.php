<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Events\TenderCreateEvent;
use ServiceBoiler\Prf\Site\Events\TenderEditEvent;
use ServiceBoiler\Prf\Site\Events\TenderStatusChangeEvent;
use ServiceBoiler\Prf\Site\Mail\Admin\Tender\TenderCreateEmail;
use ServiceBoiler\Prf\Site\Mail\Admin\Tender\TenderEditEmail;
use ServiceBoiler\Prf\Site\Mail\Admin\Tender\TenderStatusChangeEmail;
use ServiceBoiler\Prf\Site\Pdf\TenderResultPdf;

class TenderListener
{

    public function onTenderCreate(TenderCreateEvent $event)
    {
        // Отправка admin_siteистратору письма при создании нового отчета по ремонту
        //Mail::to(env('MAIL_TO_ADDRESS'))->send(new TenderCreateEmail($event->tender));
    }

    /**
     * Обработчик события:
     * Исправление отчета по ремонту
     *
     * @param TenderEditEvent $event
     */
    public function onTenderEdit(TenderEditEvent $event)
    {
        // Отправка admin_siteистратору письма при исправлении отчета по ремонту
       // Mail::to(env('MAIL_TO_ADDRESS'))->send(new TenderEditEmail($event->tender));
    }

    /**
     * Обработчик события:
     * Смена admin_siteистратором статуса отчета по ремонту
     *
     * @param TenderStatusChangeEvent $event
     */
    public function onTenderStatus(TenderStatusChangeEvent $event)
    {  
                    
        $supervisors=User::whereHas('roles', function ($query) {
                        $query
                            ->where('name', 'admin_tender_worker')
                            ->orWhere('name', 'admin_tender_view')
                            ;
                    })->pluck('email')->toArray();
                     
        switch($event->tender->status->id) {
            case 2:
                Mail::to(array_merge([$event->tender->chief->email],$supervisors))->send(new TenderStatusChangeEmail($event->tender));
                break;
            case 3:
                Mail::to(array_merge(config('site.director_email'),[$event->tender->user->email,$event->tender->chief->email],$supervisors))->send(new TenderStatusChangeEmail($event->tender));
                break;
            case 4:
            
                 $file_name=(new TenderResultPdf())->setModel($event->tender)->store('tenders');
                
                 $files[] = [
                            'file'    => $file_name,
                            'options' => [
                                'as'   => 'tenders_' .$event->tender->id .'_' .$event->tender->created_at->format('Y-m-d') .'_rendered.pdf',
                                'mime' => 'application/pdf',
                            ]];
            
            
                Mail::to(array_merge(config('site.director_email'),[$event->tender->user->email,$event->tender->chief->email],$supervisors))->send(new TenderStatusChangeEmail($event->tender, $files));
                break;
            case 5:
               Mail::to(array_merge([$event->tender->chief->email],$supervisors))->send(new TenderStatusChangeEmail($event->tender));
                break;
            case 6:
                Mail::to(array_merge(config('site.director_email'),[$event->tender->chief->email],$supervisors))->send(new TenderStatusChangeEmail($event->tender));
                break;
            case 9:
                Mail::to([$event->tender->user->email,$event->tender->chief->email])->send(new TenderStatusChangeEmail($event->tender));
                break;
            default:
                Mail::to(array_merge(config('site.director_email'),[$event->tender->user->email,$event->tender->chief->email],$supervisors))->send(new TenderStatusChangeEmail($event->tender));
                break;
        
        }
        
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            TenderCreateEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\TenderListener@onTenderCreate'
        );

        $events->listen(
            TenderEditEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\TenderListener@onTenderEdit'
        );

        $events->listen(
            TenderStatusChangeEvent::class,
            'ServiceBoiler\Prf\Site\Listeners\TenderListener@onTenderStatus'
        );
    }
}