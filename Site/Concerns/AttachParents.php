<?php

namespace ServiceBoiler\Prf\Site\Concerns;

use ServiceBoiler\Prf\Site\Models\User;

trait AttachParents
{
    /**
     * Attach multiple parents to a user
     *
     * @param mixed $parents
     */
    public function attachParents(array $parents)
    {
        foreach ($parents as $parent) {
            $this->attachParent($parent);
        }
    }


    /**
     *
     * @param mixed $parent
     */
    public function attachParent($parent)
    {
        if (is_object($parent)) {
            $parent = $parent->getKey();
        }
        if (is_array($parent)) {
            $parent = $parent['id'];
        }
        $this->parents()->attach($parent);
        
    }

    /**
     * Detach multiple parents from a user
     *
     * @param mixed $parents
     */
    public function detachParents(array $parents)
    {
        if (!$parents) {
            $parents = $this->parents()->get();
        }
        foreach ($parents as $parent) {
            $this->detachParent($parent);
        }
    }

    /**
     * @param $parents
     */
    public function syncParents($parents)
    {
        if (!is_array($parents)) {
            $parents = [$parents];
        }
        $this->parents()->sync($parents);
    }

    /**
     * Аналоги товара
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function parents()
    {
        return $this->belongsToMany(
            Product::class,
            'parents',
            'product_id',
            'parent_id');
    }

    /**
     * Удалить аналог
     *
     * @param mixed $parent
     */
    public function detachParent($parent)
    {
        if (is_object($parent)) {
            $parent = $parent->getKey();
        }
        if (is_array($parent)) {
            $parent = $parent['id'];
        }
        $this->parents()->detach($parent);
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function parents_array()
    {

        $parents = collect([]);
        if (!is_null($this->getAttribute('old_sku'))) {
            $parents->push($this->getAttribute('old_sku'));
        }

        if ($this->parents()->exists()) {
            foreach ($this->parents()->get() as $parent) {
                if ($parent->hasSku()) {
                    $parents->push($parent->getAttribute('sku'));
                }
            }
        }

        return $parents;
    }

}