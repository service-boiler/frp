<?php

namespace ServiceBoiler\Prf\Site\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AcademyQuestionRequest extends FormRequest
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
            case 'POST': {
                if(!empty($this->question['id'])) {
                return [
                    'question.text'   =>   Rule::unique('academy_questions','text')->where(function ($query) {
                                                $query->where('academy_questions.id','!=',$this->question['id']);
                                            })
                                            
                ];
                }
                    else {
                    return [
                    'question.text'   =>   Rule::unique('academy_questions','text')
                    ];
                    }
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
            'question.text'      => trans('site::academy.question.name'),
        ];
    }
}
