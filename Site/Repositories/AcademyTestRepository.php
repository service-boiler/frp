<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\AcademyTest;

class AcademyTestRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return AcademyTest::class;
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