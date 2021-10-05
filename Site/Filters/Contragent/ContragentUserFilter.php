<?php

namespace ServiceBoiler\Prf\Site\Filters\Contragent;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;
use ServiceBoiler\Prf\Site\Models\User;

class ContragentUserFilter extends Filter
{
   
    private $user_id;
    
    function apply($builder, RepositoryInterface $repository)
    {
        if (!is_null($this->user_id)) {
            $builder = $builder->where('user_id',$this->user_id);
        }
        
        return $builder;
    }
    
    public function setUserId($user_id = null)
    {
        $this->user_id = $user_id;

        return $this;
    }
}