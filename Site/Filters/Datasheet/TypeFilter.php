<?php

namespace ServiceBoiler\Prf\Site\Filters\Datasheet;

use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class TypeFilter extends Filter
{
    /**
     * @var null|int
     */
    private $type_id;

    function apply($builder, RepositoryInterface $repository)
    {
        if (!is_null($this->type_id)) {
            return $builder->whereHas('file', function ($query) {
                $query->where('type_id', $this->type_id);
            });
        } else {
            return $builder->whereRaw('false');
        }

    }

    /**
     * @param int $type_id
     * @return $this
     */
    public function setTypeId($type_id)
    {
        $this->type_id = $type_id;

        return $this;
    }
}