<?php

namespace ServiceBoiler\Prf\Site\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use ServiceBoiler\Prf\Site\Models\FileType;
use ServiceBoiler\Prf\Site\Models\Tender;

class TenderRequest extends FormRequest
{

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
            ->where('group_id', 10)
            ->where('enabled', 1)
            ->where('required', 1)
            ->get();
            
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                $rules = [
                    'tender.distributor_id'    => 'required|exists:users,id',
                    'count'    => 'required',
        
                ];
                foreach ($file_types as $type) {
                    $rules['file.' . $type->id] = 'array';
                }
                

                return $rules;
            }
            case 'PUT':
            case 'PATCH': {
                $rules = [
                    'tender.distributor_id'    => 'required|exists:users,id',
        
                ];
                foreach ($file_types as $type) {
                    $rules['file.' . $type->id] = 'array';
                }

                return $rules;
            }
            default:
                return [];
        }
    }
    public function store(StorePostRequest $request)
    {
        // The incoming request is valid...

        // Retrieve the validated input data...
        $validated = $request->validated();
    }
    
    public function withValidator($validator)
    {
        if($this->method() == 'POST') {
            $cust_names = array_pluck(array_pluck($this->input('customers.customer'),'customer'),'name');
            $counts = !empty($this->input('count')) ? array_pluck($this->input('count'),'count') : ['0'];
            $tender=Tender::where('address_name',$this->input('tender.address_name'))
             ->whereHas('customer_customer', function ($query) use ($cust_names){ 
                                                 $query->whereIn('name',$cust_names);
             })
             ->whereHas('products', function ($query) use ($counts){ 
                                                 $query->whereIn('count',$counts);
             })->orWhere('address',$this->input('tender.address'))
            ->first();
            
            $validator->after(function ($validator) use ($tender){
            
                if (!empty($tender)) {
                
                    $validator->errors()->add('dupplicate_tender', 'Такой тендер уже есть в базе. Заявка №' .$tender->id .' дата: ' .$tender->created_at->format('Y.m.d'));
                }
            });
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
            
            'tender.distributor_id'   => 'Дистрибьютор',
            'count'   => 'Оборудование',
        ];

        
        return $attributes;
    }
}
