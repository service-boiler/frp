<?php

namespace ServiceBoiler\Prf\Site\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DifficultyRequest extends FormRequest
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
                    'name' => 'required|string|max:255',
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'name' => 'required|string|max:255',
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
            'name' => trans('site::difficulty.name'),
        ];
    }
}
