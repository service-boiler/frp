<?php

namespace ServiceBoiler\Prf\Site\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use ServiceBoiler\Prf\Site\Models\User;

class RegisterFlRequest extends FormRequest
{

    //private $regex = '_^(?:(?:https?)://)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:.\d{1,3}){3})(?!(?:169.254|192.168)(?:.\d{1,3}){2})(?!172.(?:1[6-9]|2\d|3[0-1])(?:.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]-)[a-z\x{00a1}-\x{ffff}0-9]+)(?:.(?:[a-z\x{00a1}-\x{ffff}0-9]-)[a-z\x{00a1}-\x{ffff}0-9]+)(?:.(?:[a-z\x{00a1}-\x{ffff}]{2,})).?)(?::\d{2,5})?(?:[/?#]\S)?$_iuS';


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
                    'name'                      => array('required','string','max:255','regex:/([A-я]{1,30}) ([A-я]{1,30}) ([A-я]{1,30})/'),
                    'email'                     => 'sometimes:string|sometimes:email|max:191|nullable|unique:users',
                    'password'                  => 'required|string|min:6|confirmed',
                    'captcha'                   => 'required|captcha',
                    'accept'                    => 'required|accepted',
                    'web'                       => 'max:255',
                    'contact.position'          => 'max:255',
                    'contact.phone.country_id'  => 'required|exists:countries,id',
                    'contact.phone.number'      => array('required','string','size:' . config('site.phone.maxlength'),
                                                    'unique:users,phone', function ($attribute, $value, $fail) {
                                                        
                                                        
                                                        if (User::where('phone',preg_replace(config('site.phone.set.pattern'), config('site.phone.set.replacement'), $this->input('contact.phone.number')))->exists()) {
                                                            return $fail('Пользователь с телефоном '.$this->input('contact.phone.number') .'уже зарегистрирован'  );
                                                        }
                                                    }
                                                    ),
                    'contact.phone.extra'       => 'max:20',

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
            'contact.name'              => trans('site::contact.name'),
            'contact.position'          => trans('site::contact.position'),
            'contact.phone.country_id'  => trans('site::phone.country_id'),
            'contact.phone.number'      => trans('site::phone.number'),
            'contact.phone.extra'       => trans('site::phone.extra'),
            'address.sc.name'           => trans('site::address.name'),
            'address.sc.country_id'     => trans('site::address.country_id'),
            'address.sc.region_id'      => trans('site::address.region_id'),
            'address.sc.locality'       => trans('site::address.locality'),

        ];
    }
}
