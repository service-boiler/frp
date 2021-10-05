<?php

namespace ServiceBoiler\Prf\Site\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PreregMounterRequest extends FormRequest
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
        //dd($this);
        switch ($this->method()) {
            case 'PUT':
            case 'PATCH':
            case 'POST': {
                return [
                    'prereg.region_id'  => 'required|exists:regions,id',
                    'prereg.locality'       => 'required|string|max:100',
                    'prereg.email'       => 'required|string|email|max:191|unique:users,email',
                    
                ];
            }
            default:
                return [];
        }
    }

    

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'prereg.locality'       => 'Город нужно выбрать из списка,',
            'prereg.region_id' => 'Регион некорректно',
            'prereg.email' => "Email. Пользователь уже зарегистрирован на сайте",
            
        ];
    }
}
