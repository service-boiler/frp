<?php

namespace ServiceBoiler\Prf\Site\Repositories;


use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Models\EsbProductLaunch;

class EsbProductLaunchRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return EsbProductLaunch::class;
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
