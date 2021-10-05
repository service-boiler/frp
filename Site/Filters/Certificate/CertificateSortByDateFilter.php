<?php

namespace ServiceBoiler\Prf\Site\Filters\Certificate;


use ServiceBoiler\Repo\Contracts\RepositoryInterface;
use ServiceBoiler\Repo\Filter;

class CertificateSortByDateFilter extends Filter
{

    function apply($builder, RepositoryInterface $repository)
    {

        return $builder->orderByDesc("certificates.created_at");

    }

}