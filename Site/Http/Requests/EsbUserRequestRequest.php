<?php

namespace ServiceBoiler\Prf\Site\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;


class EsbUserRequestRequest extends FormRequest
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

    /*

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		switch ($this->method()) {
			case 'GET':
				{
					return [];
				}
			case 'DELETE':
				{
					return [];
				}
			case 'POST':
				{
					return [
						'type_id' => 'required',
						'recipient' => [
							'required',
							'array',
						],
					];
				}
			case 'PUT':
			case 'PATCH':
				{
					return [];
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
		];
	}

}
