<?php

namespace ServiceBoiler\Prf\Site\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class WebinarRequest extends FormRequest
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
                    'webinar.name' => 'required|string|max:99',
                    'webinar.type_id' => 'required|numeric',
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'webinar.name' => 'required|string|max:99',
                    'webinar.type_id' => 'required|numeric',
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
            'webinar.name' => trans('site::admin.name_long'),
            'webinar.type_id' => trans('site::admin.webinar.type_id_error'),
        ];
    }
}
