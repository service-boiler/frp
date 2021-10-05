<?php

namespace ServiceBoiler\Prf\Site\Repositories;

use ServiceBoiler\Repo\Eloquent\Repository;
use ServiceBoiler\Prf\Site\Filters\LostCatalogImageFilter;
use ServiceBoiler\Prf\Site\Models\Image;

class ImageRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Image::class;
    }

    /**
     * @return array
     */
    public function track(): array
    {
        return [

        ];
    }

    public function deleteLostImages()
    {
        $this->applyFilter(new LostCatalogImageFilter());
        foreach ($this->all() as $image) {
            $this->delete($image->id);
        }
    }
}