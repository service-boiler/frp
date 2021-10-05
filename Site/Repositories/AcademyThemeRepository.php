<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\AcademyThemeRequest;
use ServiceBoiler\Prf\Site\Models\AcademyTheme;

class AcademyThemeRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return AcademyTheme::class;
    }
    
     public function track():array
    {
        return [
     
        ];
    }

}