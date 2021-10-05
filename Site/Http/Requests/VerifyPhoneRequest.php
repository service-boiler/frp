<?php

namespace ServiceBoiler\Prf\Site\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyPhoneRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                   'phone_verify_code'                      => 'required_if:resend_sms,0|string|max:6',
                    ];
            }
            case 'PUT':
            case 'PATCH': {
                return [];
            }
            default:
                return [];
        }
    }

    public function messages()
    {
        return [
            'phone_verify_code.max'                    => 'Неверный код из смс',
            
        ];
    }

    public function attributes()
    {
        return [
            'phone_verify_code'                     => 'Код из смс',
           

        ];
    }
}
