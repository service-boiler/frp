<?php

namespace ServiceBoiler\Prf\Site\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StandOrderItemRequest extends FormRequest
{

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		switch ($this->method()) {
			case 'PATCH':
				{
					return [
						'order_item.*.price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
						'order_item.*.quantity' => 'sometimes|required|min:1|max:'.config('cart.item_max_quantity'),
					];
				}
			default:
				return [];
		}
	}

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes()
	{
		return [
			'order_item.*.price' => trans('site::order_item.price'),
			'order_item.*.quantity' => trans('site::order_item.quantity'),
		];
	}

}
