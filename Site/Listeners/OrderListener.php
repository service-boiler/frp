<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Events\OrderPricesAgreedEvent;
use ServiceBoiler\Prf\Site\Events\OrderStatusChangeEvent;
use ServiceBoiler\Prf\Site\Events\OrderCreateEvent;
use ServiceBoiler\Prf\Site\Events\OrderScheduleEvent;
use ServiceBoiler\Prf\Site\Events\OrderUserConfirmEvent;
use ServiceBoiler\Prf\Site\Mail\Order\AdminOrderConfirmEmail;
use ServiceBoiler\Prf\Site\Mail\User\Order\OrderStatusChangeEmail;
use ServiceBoiler\Prf\Site\Mail\Order\UserOrderCreateEmail;
use ServiceBoiler\Prf\Site\Mail\Order\AdminOrderCreateEmail;
use ServiceBoiler\Prf\Site\Mail\User\Order\OrderScheduleEmail as UserOrderScheduleEmail;

class OrderListener
{


	/**
	 * @param OrderScheduleEvent $event
	 */
	public function onOrderSchedule(OrderScheduleEvent $event)
	{
		$event->order->schedules()->create([
			'action_id' => 2,
			'url' => preg_split("/:\/\//", route('api.orders.show', $event->order))[1],
		]);
		$event->order->setAttribute('status_id', 2);
		$event->order->save();
		Mail::to($event->order->user->email)->send(new UserOrderScheduleEmail($event->order));
	}

	/**
	 * @param OrderCreateEvent $event
	 */
	public function onOrderCreate(OrderCreateEvent $event)
	{
		Mail::to(env('MAIL_TO_ADDRESS'))->send(new AdminOrderCreateEmail($event->order));

		if ($event->order->user->email) {
			Mail::to($event->order->user->email)->send(new UserOrderCreateEmail($event->order));
		}

        if($event->order->address->email){
            Mail::to($event->order->address->email)->send(new UserOrderCreateEmail($event->order));
        }
	}

	/**
	 * @param OrderStatusChangeEvent $event
	 */
	public function onOrderStatusChange(OrderStatusChangeEvent $event)
	{
		Mail::to($event->order->user->email)->send(new OrderStatusChangeEmail($event->order));

		if ($event->order->getAttribute('status_id') == 9) {
			$event->order->recalculate();
		}
	}

	/**
	 * @param OrderUserConfirmEvent $event
	 */
	public function onOrderConfirmed(OrderUserConfirmEvent $event)
	{
		Mail::to(env('MAIL_ORDER_ADDRESS'))->send(new AdminOrderConfirmEmail($event->order));
	}

	/**
	 * @param Dispatcher $events
	 */
	public function subscribe(Dispatcher $events)
	{

		$events->listen(
			OrderCreateEvent::class,
			'ServiceBoiler\Prf\Site\Listeners\OrderListener@onOrderCreate'
		);

		$events->listen(
			OrderStatusChangeEvent::class,
			'ServiceBoiler\Prf\Site\Listeners\OrderListener@onOrderStatusChange'
		);

		$events->listen(
			OrderScheduleEvent::class,
			'ServiceBoiler\Prf\Site\Listeners\OrderListener@onOrderSchedule'
		);

		$events->listen(
			OrderUserConfirmEvent::class,
			'ServiceBoiler\Prf\Site\Listeners\OrderListener@onOrderConfirmed'
		);

	}
}