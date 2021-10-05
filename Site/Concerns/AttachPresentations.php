<?php

namespace ServiceBoiler\Prf\Site\Concerns;

trait AttachPresentations
{
    /**
     * Attach multiple presentation to a user
     *
     * @param mixed $presentation
     */
    public function attachPresentations(array $presentations)
    {      
        foreach ($presentations as $presentation) {
            $this->attachPresentation($presentation);
        }
    }

    /**
     * Alias to eloquent many-to-many relation's attach() method.
     *
     * @param mixed $presentation
     */
    public function attachPresentation($presentation)
    {
        if (is_object($presentation)) {
            $presentation = $presentation->getKey();
        }
        if (is_array($presentation)) {
            $presentation = $presentation['id'];
        }
       
        $this->presentations()->attach($presentation);
    }

    /**
     * Detach multiple presentation from a user
     *
     * @param mixed $presentation
     */
    public function detachPresentations(array $presentations)
    {
        if (!$presentations) {
            $presentations = $this->presentations()->get();
        }
        foreach ($presentations as $presentation) {
            $this->detachPresentation($presentation);
        }
    }

    /**
     * @param $presentation
     */
    public function syncPresentations($presentations)
    {
        if (!is_array($presentations)) {
            $presentations = [$presentations];
        }
        
        $this->presentations()->sync($presentations, true);
    }


    /**
     * Alias to eloquent many-to-many relation's detach() method.
     *
     * @param mixed $presentation
     */
    public function detachPresentation($presentation)
    {
        if (is_object($presentation)) {
            $presentation = $presentation->getKey();
        }
        if (is_array($presentation)) {
            $presentation = $presentation['id'];
        }
        $this->presentations()->detach($presentation);
    }
}