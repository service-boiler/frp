<?php

namespace ServiceBoiler\Prf\Site\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use ServiceBoiler\Prf\Site\Rules\StorehouseUrlLoad;

class StorehouseUrlRequest extends FormRequest
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
            case 'POST': {
                return [
                    'url' => [
                        'required',
                        'url',
                        new StorehouseUrlLoad,
                    ],
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
        return [
            'path.required' => trans('site::storehouse_product.error.load.empty'),
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'url' => trans('site::storehouse.url'),
        ];
    }
}
