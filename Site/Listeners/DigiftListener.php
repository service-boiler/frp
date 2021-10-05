<?php

namespace ServiceBoiler\Prf\Site\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use ServiceBoiler\Prf\Site\Events\Digift\ExceptionEvent;
use ServiceBoiler\Prf\Site\Events\Digift\ExpenseApiExceptionEvent;
use ServiceBoiler\Prf\Site\Events\Digift\UserBalanceMismatchEvent;
use ServiceBoiler\Prf\Site\Mail\Admin\Digift\ApiErrorEmail;
use ServiceBoiler\Prf\Site\Mail\Admin\Digift\ExceptionEmail;
use ServiceBoiler\Prf\Site\Mail\Admin\Digift\UserBalanceMismatchEmail;

class DigiftListener
{

	/**
	 * @param UserBalanceMismatchEvent $event
	 */
	public function onUserBalanceMismatch(UserBalanceMismatchEvent $event)
	{
		Mail::to(env('MAIL_BONUS_ADDRESS'))
			->cc(env('MAIL_DIRECTOR_ADDRESS '))
			->send(new UserBalanceMismatchEmail($event->digiftUser, $event->balance));
		
        Mail::to(env('MAIL_DEVEL_ADDRESS'))
			->send(new UserBalanceMismatchEmail($event->digiftUser, $event->balance));
		//TODO Раскомментить

	}

	/**
	 * @param ExpenseApiExceptionEvent $event
	 */
	public function onDigiftApiError(ExpenseApiExceptionEvent $event)
	{
		Mail::to(env('MAIL_BONUS_ADDRESS'))
			->send(new ApiErrorEmail($event->request_data, $event->exception));
            
		Mail::to(env('MAIL_DEVEL_ADDRESS'))
			->send(new ApiErrorEmail($event->request_data, $event->exception));
	}

	/**
	 * @param ExceptionEvent $event
	 */
	public function onException(ExceptionEvent $event)
	{
		Mail::to(env('MAIL_BONUS_ADDRESS'))
			->send(new ExceptionEmail($event->method, $event->exception));
		Mail::to(env('MAIL_DEVEL_ADDRESS'))
			->send(new ExceptionEmail($event->method, $event->exception));
	}

	/**
	 * @param Dispatcher $events
	 */
	public function subscribe(Dispatcher $events)
	{

		$events->listen(
			UserBalanceMismatchEvent::class,
			'ServiceBoiler\Prf\Site\Listeners\DigiftListener@onUserBalanceMismatch'
		);

		$events->listen(
			ExpenseApiExceptionEvent::class,
			'ServiceBoiler\Prf\Site\Listeners\DigiftListener@onDigiftApiError'
		);

		$events->listen(
			ExceptionEvent::class,
			'ServiceBoiler\Prf\Site\Listeners\DigiftListener@onException'
		);

	}
}