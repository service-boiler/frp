<?php

namespace ServiceBoiler\Prf\Site\Concerns;

trait AttachStages
{
    /**
     * Attach multiple stage to a user
     *
     * @param mixed $stage
     */
    public function attachStages(array $stages)
    {      
        foreach ($stages as $stage) {
            $this->attachStage($stage);
        }
    }

    /**
     * Alias to eloquent many-to-many relation's attach() method.
     *
     * @param mixed $stage
     */
    public function attachStage($stage)
    {
        if (is_object($stage)) {
            $stage = $stage->getKey();
        }
        if (is_array($stage)) {
            $stage = $stage['id'];
        }
       
        $this->stages()->attach($stage);
    }

    /**
     * Detach multiple stage from a user
     *
     * @param mixed $stage
     */
    public function detachStages(array $stages)
    {
        if (!$stages) {
            $stages = $this->stages()->get();
        }
        foreach ($stages as $stage) {
            $this->detachStage($stage);
        }
    }

    /**
     * @param $stage
     */
    public function syncStages($stages)
    {
        if (!is_array($stages)) {
            $stages = [$stages];
        }
        
        $this->stages()->sync($stages, true);
    }


    /**
     * Alias to eloquent many-to-many relation's detach() method.
     *
     * @param mixed $stage
     */
    public function detachStage($stage)
    {
        if (is_object($stage)) {
            $stage = $stage->getKey();
        }
        if (is_array($stage)) {
            $stage = $stage['id'];
        }
        $this->stages()->detach($stage);
    }
}