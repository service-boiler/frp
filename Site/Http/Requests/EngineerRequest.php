<?php

namespace ServiceBoiler\Prf\Site\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use ServiceBoiler\Prf\Site\Models\Certificate;
use ServiceBoiler\Prf\Site\Models\CertificateType;

class EngineerRequest extends FormRequest
{

    private $_certificate_types;

    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->_certificate_types = CertificateType::query()->pluck('name', 'id')->toArray();
    }

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
            case 'POST':{
                $rules = [
                    'user.name'       => 'required|string|max:255',
                    'user.phone'      => array('required','string','size:' . config('site.phone.maxlength'),
                        'unique:users,phone,'.$this->user()->id, function ($attribute, $value, $fail) {
                            if (\ServiceBoiler\Prf\Site\Models\User::where('phone',preg_replace(config('site.phone.set.pattern'), config('site.phone.set.replacement'), $this->input('user.phone')))
                                ->exists()) {
                                return $fail('Пользователь с телефоном '.$this->input('user.phone') .'уже зарегистрирован'  );
                            }
                        }
                    ),
                    'user.email'      => 'sometimes|nullable|string|email|max:255|unique:users,email',
                    'user.region_id'    => 'sometimes|nullable|exists:regions,id',
                ];
                return $rules;
            }
            case 'PUT':
            case 'PATCH': {
                $rules = [
                    'user.name'       => 'required|string|max:255',
                    'user.phone'      => array('required','string','size:' . config('site.phone.maxlength'),
                        'unique:users,phone,'.$this->user()->id, function ($attribute, $value, $fail) {
                            if (\ServiceBoiler\Prf\Site\Models\User::where('phone',preg_replace(config('site.phone.set.pattern'), config('site.phone.set.replacement'), $this->input('user.phone')))
                                ->where('id','<>',$this->route()->parameter('engineer')->id)->exists()) {
                                return $fail('Пользователь с телефоном '.$this->input('user.phone') .'уже зарегистрирован'  );
                            }
                        }
                    ),
                    'user.email'      => 'sometimes|string|email|max:255|unique:users,email,'.$this->route()->parameter('engineer')->id,
                    'user.region_id'    => 'sometimes|nullable|exists:regions,id',
                ];
                return $rules;
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
        $messages = [];
        return $messages;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'user.name'       => trans('site::user.name'),
            'user.email' => trans('site::user.email'),
            'user.phone'      => trans('site::user.phone'),
            'user.region_id'    => trans('site::user.region_id'),
        ];
    }
}
