<?php

namespace ServiceBoiler\Prf\Site\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class DigiftBonusRequest extends FormRequest
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

			case 'POST':
				{
					return [
						'digift_bonus.operationValue' => 'required|numeric|min:1',
					];
				}

			default:
				return [];
		}
	}

	/**
	 * Get custom messages for validator errors.
	 *
	 * @return array
	 */
	public function messages()
	{
		return [];
	}

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes()
	{

		return [
			'digift_bonus.operationValue' => trans('site::digift_bonus.operationValue'),
		];
	}
}
