<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\HeadBannerBlock;

class HeadBannerBlockRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return HeadBannerBlock::class;
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