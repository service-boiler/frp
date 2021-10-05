<?php

namespace ServiceBoiler\Prf\Site\Filters\User;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class UserHasAddressRoleFilter extends Filter
{
    private $role_name;
    
    function apply($builder, RepositoryInterface $repository)
    {
        if (!is_null($this->role_name)) {
        $builder = $builder
            ->whereHas("addresses", function($query){
                $query->where($this->role_name,'1')
                        ->where('type_id','2')
                        ->where('approved','1');
            });
        }
        
        return $builder;
    }
    
    public function setRoleName($role_name = null)
    {
        $this->role_name = $role_name;

        return $this;
    }
}