<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\TemplateType;

class TemplateTypeRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return TemplateType::class;
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