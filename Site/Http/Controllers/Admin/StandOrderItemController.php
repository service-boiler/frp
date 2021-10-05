<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\StandOrderItemRequest;
use ServiceBoiler\Prf\Site\Models\StandOrderItem;

class StandOrderItemController extends Controller
{

	use AuthorizesRequests;

	public function update(StandOrderItemRequest $request, StandOrderItem $standOrderItem)
	{  // dd($request);
		$standOrderItem->fill($request->input('standOrderItem.' . $standOrderItem->getKey()));

		if ($standOrderItem->isDirty()) {

			$standOrderItem->syncChanges();
			$changes = [trans('site::stand_order.item_message.product', ['product' => $standOrderItem->product->name()])];

			foreach ($standOrderItem->getChanges() as $key => $value) {
				$changes[] = trans('site::stand_order.item_message.item', [
					'column' => trans('site::stand_order.item_message.' . $key) . trans('site::stand_order.item_message.columns.' . $key),
					'original' => $standOrderItem->getOriginal($key),
					'change' => $value,
				]);
			}

			$text = implode("\r\n", $changes);
			$receiver_id = $request->user()->getKey();
           
            
            if ($standOrderItem->save()) { 
				$standOrderItem->standOrder->messages()->save($request->user()->outbox()->create(compact('text', 'receiver_id')));
			}

		}

		return redirect()->route('admin.stand-orders.show', $standOrderItem->standOrder)->with('success', trans('site::stand_order.item_message.updated'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param StandOrderItemRequest $request
	 * @param  StandOrderItem $standOrderItem
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 * @throws \Exception
	 */
	public function destroy(StandOrderItemRequest $request, StandOrderItem $standOrderItem)
	{
		$this->authorize('delete', $standOrderItem);
		if ($standOrderItem->delete()) {

			$changes = [
				trans('site::stand_order.item_message.delete', ['product' => $standOrderItem->product->name()]),
			];
			foreach (['price', 'quantity', 'weeks_delivery'] as $key) {
				$changes[] = trans('site::stand_order.item_message.' . $key) . trans('site::stand_order.item_message.columns.' . $key) . $standOrderItem->getOriginal($key);
			}

			$text = implode("\r\n", $changes);

			$receiver_id = $request->user()->getKey();
            
		//$standOrderItem->standOrder->messages()->save($request->user()->outbox()->create(compact('text', 'receiver_id')));

			$json['redirect'] = route('admin.stand-orders.show', $standOrderItem->standOrder);
		} else {
			$json['errors'] = trans('site::stand_order.item_message.error_deleted');
		}

		return response()->json($json, Response::HTTP_OK);

	}

}