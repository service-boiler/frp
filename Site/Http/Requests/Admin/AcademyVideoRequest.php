<?php

namespace ServiceBoiler\Prf\Site\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AcademyVideoRequest extends FormRequest
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
                    'video.name' => 'required|string|max:150',
                    'video.link' => 'required|string|max:150',
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'video.name' => 'required|string|max:150',
                    'video.link' => 'required|string|max:150',
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
            'video.name' => trans('site::admin.name_long'),
            'video.link' => trans('site::academy.video.link'),
        ];
    }
}
