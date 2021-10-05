<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\Certificate\CertificateSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Certificate\CertificateTypeSelectFilter;
use ServiceBoiler\Prf\Site\Models\Certificate;

class CertificateRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Certificate::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [
            CertificateSearchFilter::class,
            CertificateTypeSelectFilter::class,
        ];
    }
}