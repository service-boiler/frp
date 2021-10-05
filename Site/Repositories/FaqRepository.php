<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\Faq;

class FaqRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Faq::class;
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