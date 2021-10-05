<?php

namespace ServiceBoiler\Prf\Site\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use ServiceBoiler\Prf\Site\Models\FileType;

class PartnerPlusRequestRequest  extends FormRequest
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
        $file_types = FileType::query()
            ->where('group_id', 15)
            ->where('enabled', 1)
            ->where('required', 1)
            ->get();
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                    $rules = ['partnerPlusRequest.partner_contragent_id' => [
                        'required',
                        'exists:contragents,id',
                        Rule::exists('contragents', 'id'),
                    ],
                    ];
                return $rules;
            }
            case 'PUT':
            case 'PATCH': {
                return [];
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
        $attributes = [
            'partnerPlusRequest.partner_contragent_id'   => trans('site::repair.contragent_id'),
            
        ];

        return $attributes;
    }
}
