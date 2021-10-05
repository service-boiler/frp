<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\AcademyQuestion;

class AcademyQuestionRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return AcademyQuestion::class;
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