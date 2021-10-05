<?php

namespace ServiceBoiler\Prf\Site\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MounterRequest extends FormRequest
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

            case 'PATH':
            case 'PUT': {
                return [
                    'mounter.client'     => 'required|string|max:255',
                    'mounter.country_id' => 'required|exists:countries,id',
                    'mounter.status_id'  => 'required|exists:mounter_statuses,id',
                    'mounter.phone'      => 'required|string|size:' . config('site.phone.maxlength'),
                    'mounter.address'    => 'required|string|max:255',
                    'mounter.model'      => 'sometimes|nullable|max:255',
                    'mounter.mounter_at' => 'required|date_format:"d.m.Y"',
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
            'mounter.client'     => trans('site::mounter.client'),
            'mounter.country_id' => trans('site::mounter.country_id'),
            'mounter.status_id'  => trans('site::mounter.status_id'),
            'mounter.phone'      => trans('site::mounter.phone'),
            'mounter.address'    => trans('site::mounter.address'),
            'mounter.model'      => trans('site::mounter.model'),
            'mounter.mounter_at' => trans('site::mounter.mounter_at'),
        ];
    }
}
