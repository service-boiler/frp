<?php

namespace ServiceBoiler\Prf\Site\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use ServiceBoiler\Prf\Site\Rules\ActMountingMinCostRule;

class ActRequest extends FormRequest
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
                    'mountings'   => [
                        'required',
                        'array'
                    ],
                    'mountings.*' => [
                        'exists:mountings,id',
                        new ActMountingMinCostRule()
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
        return [
            'mountings.required' => trans('site::mounting.error.act.required')
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
            'mountings' => trans('site::mounting.mountings'),
        ];
    }
}
