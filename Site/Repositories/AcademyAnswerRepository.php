<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\AcademyAnswer;

class AcademyAnswerRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return AcademyAnswer::class;
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