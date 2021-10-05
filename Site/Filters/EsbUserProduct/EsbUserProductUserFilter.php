<?php

namespace ServiceBoiler\Prf\Site\Filters\EsbUserProduct;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;
use ServiceBoiler\Prf\Site\Models\User;

class EsbUserProductUserFilter extends Filter
{

    protected $esbUser;

    function apply($builder, RepositoryInterface $repository)
    {
        
        if($this->esbUser ) {
            $builder = $builder->where('user_id', $this->esbUser->id);
        }
        return $builder;
    }

    public function setUser(User $esbUser)
    {
        $this->esbUser = $esbUser;

        return $this;
    }
}