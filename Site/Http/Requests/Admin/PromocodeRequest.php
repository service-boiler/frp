<?php

namespace ServiceBoiler\Prf\Site\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PromocodeRequest extends FormRequest
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
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'promocode.name' => 'required|string|max:50',
                    'promocode.short_token' => Rule::unique('promocodes','short_token')->where(function ($query) {
                                                $query->whereNotNull('promocodes.short_token');
                                            })
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'promocode.name' => 'required|string|max:50',
                    'promocode.short_token' => array('unique:promocodes,short_token'),
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
            'promocode.name' => trans('site::admin.promocodes.name'),
            'promocode.short_token' => trans('site::admin.promocodes.short_token'),
        ];
    }
}
