<?php

namespace ServiceBoiler\Prf\Site\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EsbContractTypeRequest extends FormRequest
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
                    'name'       => 'required|string|max:200',
                    'color'       => 'required|string|max:10',
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'name'       => 'required|string|max:200',
                    'color'       => 'required|string|max:10',
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
            'name'       => trans('site::user.esb_contract_type.name'),
            'color' => trans('site::user.esb_contract_type.color'),
            
        ];
    }
}
