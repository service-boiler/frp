<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\VideoBlock;

class VideoBlockRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return VideoBlock::class;
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