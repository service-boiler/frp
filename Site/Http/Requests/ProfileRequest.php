<?php

namespace ServiceBoiler\Prf\Site\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use ServiceBoiler\Prf\Site\Models\User;

class ProfileRequest extends FormRequest
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
                    'name'                  => 'required|string|max:255',
                    'email'                 => 'string|email|max:255|unique:users',
                    'phone'                 =>       'max:10',
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'name'                  => 'required|string|max:255',
                    'email'                 => 'sometimes:string|sometimes:email|max:255|unique:users,email,'.$this->user()->id,
                    'phone'                 => 'max:10|unique:users,phone,'.$this->user()->id,
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
            'phone'                  => trans('site::user.phone'),
            'name'                  => trans('site::user.name'),
            'email'                 => trans('site::user.email'),
            
        ];
    }
}
