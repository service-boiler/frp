<?php

namespace ServiceBoiler\Prf\Site\Filters\Product;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class LimitFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {
        if ($this->canTrack() && $this->filled($this->name())) {

            $limit = $this->get($this->name());
            $builder = $builder->limit($limit);
        }

        return $builder;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'limit';
    }
}