<?php

namespace ServiceBoiler\Prf\Site\Filters;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;
use ServiceBoiler\Prf\Site\Models\User;

class UserFilter extends Filter
{
    /**
     * @var User|null
     */
    private $user;

    function apply($builder, RepositoryInterface $repository)
    {
        if (!is_null($this->user)) {
            $builder = $builder->whereUserId($this->user->id);
        } else {
            $builder->whereRaw('false');
        }
        return $builder;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }
}