<?php

namespace ServiceBoiler\Prf\Site\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AcademyPresentationRequest extends FormRequest
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
            case 'POST': /*{
                if(!empty($this->presentation['id'])) {
                return [
                    'presentation.name'   =>   Rule::unique('academy_presentations','name')->where(function ($query) {
                                                $query->where('academy_presentations.id','!=',$this->presentation['id']);
                                            })
                        ];
                }
                    else {
                    return [
                    'presentation.name'   =>   Rule::unique('academy_presentations','name')
                    ];
                    }
            } */
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
            'presentation.text'      => trans('site::academy.presentation.name'),
        ];
    }
}
