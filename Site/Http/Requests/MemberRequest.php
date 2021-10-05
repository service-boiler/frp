<?php

namespace ServiceBoiler\Prf\Site\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberRequest extends FormRequest
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
                    'member.type_id'                    => 'required|exists:event_types,id',
                    'member.region_id'                  => 'required|exists:regions,id',
                    'member.city'                       => 'required|string|max:100',
                    'member.name'                       => 'required|string|max:255',
                    'member.date_from'                  => 'required|date',
                    'member.date_to'                    => 'required|date|after_or_equal:date_from',
                    'member.email'                      => 'required|string|max:50',
                    'member.contact'                    => 'required|string|max:255',
                    'member.phone'                      =>'required|string|size:'.config('site.phone.maxlength'),
                    'member.count'                      => 'required|numeric|min:1|max:50',
                    'member.address'                    => 'nullable|max:255',
                    'member.participant.*.name'         => 'required|string|max:100',
                    'member.participant.*.headposition' => 'required|string|max:100',
                    'member.participant.*.phone'        => 'nullable|size:'.config('site.phone.maxlength'),
                    'member.participant.*.email'        => 'nullable|max:50|email',

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
            'member.date_to' => [
                'after_or_equal' => trans('site::member.error.date_to.after_or_equal')
            ],
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
            'member.type_id'                    => trans('site::member.type_id'),
            'member.region_id'                  => trans('site::member.region_id'),
            'member.city'                       => trans('site::member.city'),
            'member.name'                       => trans('site::member.name'),
            'member.email'                      => trans('site::member.email'),
            'member.contact'                    => trans('site::member.contact'),
            'member.phone'                      => trans('site::member.phone'),
            'member.count'                      => trans('site::member.count'),
            'member.address'                    => trans('site::member.address'),
            'member.date_from'                  => trans('site::member.date_from'),
            'member.date_to'                    => trans('site::member.date_to'),
            'member.participant.*.name'         => trans('site::participant.name'),
            'member.participant.*.headposition' => trans('site::participant.headposition'),
            'member.participant.*.phone'        => trans('site::participant.phone'),
            'member.participant.*.email'        => trans('site::participant.email'),
        ];
    }
}
