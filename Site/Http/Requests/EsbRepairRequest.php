<?php

namespace ServiceBoiler\Prf\Site\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use ServiceBoiler\Prf\Site\Models\FileType;

class EsbRepairRequest extends FormRequest
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
            return ['esb_repair.date_repair'   => 'required|date_format:"d.m.Y"',
                    'esb_repair.esb_user_product_id'   => 'exists:esb_user_products,id',
                    'esb_repair.service_id'   => 'exists:users,id',
                    'esb_repair.client_id'   => 'exists:users,id',
                    'esb_repair.engineer_id'   => 'exists:users,id',];
                    
            }
            case 'PUT':
            case 'PATCH': {
                return ['esb_repair.date_repair'   => 'required|date_format:"d.m.Y"',
                    'esb_repair.esb_user_product_id'   => 'exists:esb_user_products,id',
                    'esb_repair.service_id'   => 'exists:users,id',
                    'esb_repair.client_id'   => 'exists:users,id',
                    'esb_repair.engineer_id'   => 'exists:users,id',];
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
            'accept'                 => trans('site::user.accept'),
            
        ];

        $file_types = FileType::query()
            ->where('group_id', 14)
            ->where('enabled', 1)
            ->get();
        foreach ($file_types as $type) {
            $attributes['file.' . $type->id] = $type->name;
        }

        return $attributes;
    }
}
