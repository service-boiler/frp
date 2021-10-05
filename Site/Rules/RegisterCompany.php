<?php

namespace ServiceBoiler\Prf\Site\Rules;

use Illuminate\Contracts\Validation\Rule;

class RegisterCompany implements Rule
{
    private $authorization_types;
    private $errors;
   
    public function passes($attribute, $value)
    {
       
       foreach($value as $authorization) {
           if(is_array($value) && is_array($this->authorization_types) && array_key_exists($authorization,$this->authorization_types)){
                foreach($this->authorization_types as $row) {
                    if(!is_array($row)) {
                       $this->errors[]=$authorization;
                       
                    }
                }
           } else {
            $this->errors[]=$authorization;
            
            }
        }
        if($this->errors) {
        return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function setAuthorizationTypes($authorization_types)
    {
        $this->authorization_types=$authorization_types;
        return $this;
    }
    public function message()
    {
        
        return 'Должно быть выбрано оборудование в заявке на авторизацию';
        
    }
}