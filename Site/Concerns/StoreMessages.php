<?php

namespace ServiceBoiler\Prf\Site\Concerns;

use ServiceBoiler\Prf\Site\Contracts\Messagable;
use ServiceBoiler\Prf\Site\Events\MessageCreateEvent;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;

trait StoreMessages
{

	/**
	 * @param \ServiceBoiler\Prf\Site\Http\Requests\MessageRequest $request
	 * @param \ServiceBoiler\Prf\Site\Contracts\Messagable $messagable
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
	public function storeMessage(MessageRequest $request, Messagable $messagable)
	{ 
		$messagable->messages()->save($message = $request->user()->outbox()->create($request->input('message')));
      
       
       if ($message->personal == 0) {

			event(new MessageCreateEvent($message));
			$identifier = '#messages';
			$toast = trans('site::message.created');
		} elseif(class_basename($messagable)=='Tender') {
            event(new MessageCreateEvent($message));
            $identifier = '#comments';
			$toast = trans('site::message.comment_created');
		} else {
			$identifier = '#comments';
			$toast = trans('site::message.comment_created');
		}

		return response()->json([
			'append' => [
				$identifier => view('site::message.create.row')
					->with('message', $message)
					->render(),
				// '#toasts' => view('site::message.toast')
					// ->with('message', $toast)
					// ->render(),
			],
		]);
	}
}