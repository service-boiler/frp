<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\FileGroup;

class FileGroupRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return FileGroup::class;
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