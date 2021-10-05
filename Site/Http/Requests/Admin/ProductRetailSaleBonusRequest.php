<?php

namespace ServiceBoiler\Prf\Site\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRetailSaleBonusRequest extends FormRequest
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

            case 'POST':
            case 'PUT':
            case 'PATCH': {
                return [
                    'retail_sale_bonus.product_id' => 'required|exists:products,id',
                    'retail_sale_bonus.value'      => 'required|numeric|min:0|max:10000',
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
            'retail_sale_bonus.product_id' => trans('site::product.retail_sale_bonus.product_id'),
            'retail_sale_bonus.value'      => trans('site::product.retail_sale_bonus.value'),
        ];
    }
}
