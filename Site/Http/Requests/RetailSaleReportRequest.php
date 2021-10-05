<?php

namespace ServiceBoiler\Prf\Site\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use ServiceBoiler\Prf\Site\Models\FileType;

class RetailSaleReportRequest extends FormRequest
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
            ->where('group_id', 9)
            ->where('enabled', 1)
            ->where('required', 1)
            ->get();
            
        switch ($this->method()) {

            case 'POST': {
                $rules = [
                    
                    'report.contragent_id' => [
                    	'sometimes',
                        'required',
                        'exists:contragents,id',
                        Rule::exists('contragents', 'id')->where(function ($query) {
                            /** @var \Illuminate\Database\Query\Builder $query */
                            $query->where('contragents.user_id', $this->user()->id);
                        }),
                    ],
                    'report.product_id'    => [
                        'required',
                        'exists:products,id',
                    ],
                    
                    'report.date_trade'    => 'required|date_format:"d.m.Y"',
                    


                ];
                foreach ($file_types as $file_type) {

                    $rules['file.' . $file_type->id] = 'required|array';
                }

               
                return $rules;
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
            'accept'                   => trans('site::report.accept'),
            'report.source_id'       => trans('site::report.source_id'),
            'report.source_other'    => trans('site::report.source_other'),
            'report.contragent_id'   => trans('site::report.contragent_id'),
            'report.client'          => trans('site::report.client'),
            'report.country_id'      => trans('site::report.country_id'),
            'report.address'         => trans('site::report.address'),
            'report.phone_primary'   => trans('site::report.phone_primary'),
            'report.date_trade'      => trans('site::report.date_trade'),
            'report.date_report'   => trans('site::report.date_report'),
        ];
        $file_types = FileType::query()
            ->where('group_id', 9)
            ->where('enabled', 1)
            ->get();
        foreach ($file_types as $type) {
            $attributes['file.' . $type->id] = $type->name;
        }

        return $attributes;
    }
}
