<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\IndexQuadroBlock;

class IndexQuadroBlockRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return IndexQuadroBlock::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [

        ];
    }
}