<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\EsbContractTemplate;

class EsbContractTemplateRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return EsbContractTemplate::class;
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