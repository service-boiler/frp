<?php

namespace ServiceBoiler\Prf\Site\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            case 'PUT': 
            case 'PATCH':
            case 'POST': {
                return [
                    'review.name'    => 'required|string|max:49',
                    'review.city'    => 'required|string|max:19',
                    'review.message' => 'required|string|max:1200',
                    'captcha'            => 'required|captcha',
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
            'captcha' => trans('site::mounter.error.captcha'),
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
            'captcha'            => trans('site::mounter.captcha'),
            'review.name'    => trans('site::feedback.name'),
            'review.message' => trans('site::feedback.message'),
        ];
    }
}
