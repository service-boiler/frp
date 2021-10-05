<?php

namespace ServiceBoiler\Prf\Site\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use ServiceBoiler\Prf\Site\Models\EsbUser;

class RegisterEsbRequest extends FormRequest
{

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
                    'last_name'                      => array('required','string','max:30','regex:/([A-я]{1,30})/'),
                    'email'                     => 'sometimes:string|sometimes:email|max:191|nullable|unique:esb_users',
                    'password'                  => 'required|string|min:6|confirmed',
                    //'captcha'                   => 'required|captcha',
                    'accept'                    => 'required|accepted',
                    'phone'      => array('required','string','size:' . config('site.phone.maxlength'),
                                                    'unique:esb_users,phone', function ($attribute, $value, $fail) {
                                                        if (EsbUser::where('phone',preg_replace(config('site.phone.set.pattern'), config('site.phone.set.replacement'), $this->input('contact.phone.number')))->exists()) {
                                                            return $fail('Пользователь с телефоном '.$this->input('contact.phone.number') .'уже зарегистрирован'  );
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
            'name'                      => trans('site::user.error.name_fl'),
            'email'                     => trans('site::user.email'),
            'password'                  => trans('site::user.password'),
            'captcha'                   => trans('site::register.captcha'),
            
        ];
    }
}
