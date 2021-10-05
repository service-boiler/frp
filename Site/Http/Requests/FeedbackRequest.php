<?php

namespace ServiceBoiler\Prf\Site\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackRequest extends FormRequest
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
                    'name'    => 'required|string|max:55',
                    'email'   => 'required|string|email|max:35',
                    'phone'   => 'required|string|max:25',
                    'theme'   => 'required|string|max:105',
                    'message' => 'required|string|max:1001',
                    'captcha' => 'required|captcha',
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
            'name'    => trans('site::feedback.name'),
            'email'   => trans('site::feedback.email'),
            'captcha' => trans('site::register.captcha'),
            'message' => trans('site::feedback.message'),
        ];
    }
}
