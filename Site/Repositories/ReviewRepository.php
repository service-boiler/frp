<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;

use ServiceBoiler\Prf\Site\Models\Review;

class ReviewRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Review::class;
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