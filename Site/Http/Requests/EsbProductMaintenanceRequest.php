<?php

namespace ServiceBoiler\Prf\Site\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use ServiceBoiler\Prf\Site\Models\FileType;

class EsbProductMaintenanceRequest extends FormRequest
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
            ->where('group_id', 14)
            ->where('enabled', 1)
            ->where('required', 1)
            ->get();
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                $rules = collect([]);
                foreach ($file_types as $type) {
                    $rules['file.' . $type->id] = 'required|array';
                }

                return $rules->toArray();
            }
            case 'PUT':
            case 'PATCH': {
                $rules = collect([]);
                foreach ($file_types as $type) {
                    $rules['file.' . $type->id] = 'required|array';
                }
                
                return $rules->toArray();
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
