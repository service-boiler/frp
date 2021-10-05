<?php

namespace ServiceBoiler\Prf\Site\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use ServiceBoiler\Prf\Site\Models\User;

class NewPhoneRequest extends FormRequest
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
                    'phone'      => array('required','string','size:' . config('site.phone.maxlength'),
                                                    'unique:users,phone', function ($attribute, $value, $fail) {
                                                        if (User::where('phone',preg_replace(config('site.phone.set.pattern'), config('site.phone.set.replacement'), $this->input('phone')))->exists()) {
                                                            return $fail('Пользователь с телефоном '.$this->input('phone') .'уже зарегистрирован'  );
                                                        }
                                                    }
                                                    ),

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

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'captcha'                    => trans('site::register.error.captcha'),
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
            'accept'                    => trans('site::register.help.accept'),
            
            
        ];
    }
}
