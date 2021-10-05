<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\TemplateFile;

class TemplateFileRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return TemplateFile::class;
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