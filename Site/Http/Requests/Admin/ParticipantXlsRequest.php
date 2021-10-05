<?php

namespace ServiceBoiler\Prf\Site\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use ServiceBoiler\Prf\Site\Rules\ParticipantXlsLoad;

class ParticipantXlsRequest extends FormRequest
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
                    'path' => [
                        'required',
                        'mimes:xls,xlsx',
                        'max:' . config('site.files.size', 8092),
                        new ParticipantXlsLoad
                    ],
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
            'participants'       => trans('site::events.participant'),
            'separator_row' => trans('site::messages.separator.row'),
        ];
    }
}
