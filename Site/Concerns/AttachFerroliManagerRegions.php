<?php

namespace ServiceBoiler\Prf\Site\Concerns;

trait AttachFerroliManagerRegions
{
    /**
     * Attach multiple regions to a user
     *
     * @param mixed $regions
     */
    public function attachFerroliManagerRegions(array $regions)
    {
        foreach ($regions as $region) {
            $this->attachRegion($region);
        }
    }

    /**
     * Alias to eloquent many-to-many relation's attach() method.
     *
     * @param mixed $region
     */
    public function attachFerroliManagerRegion($region)
    {
        if (is_object($region)) {
            $region = $region->getKey();
        }
        if (is_array($region)) {
            $region = $region['id'];
        }
        $this->ferroliManagerRegions()->attach($region);
    }

    /**
     * Detach multiple regions from a user
     *
     * @param mixed $regions
     */
    public function detachFerroliManagerRegions(array $regions)
    {
        if (!$regions) {
            $regions = $this->ferroliManagerRegions()->get();
        }
        foreach ($regions as $region) {
            $this->detachFerroliManagerRegion($region);
        }
    }

    /**
     * @param $regions
     */
    public function syncFerroliManagerRegions($regions)
    {
        if (!is_array($regions)) {
            $regions = [$regions];
        }
        $this->ferroliManagerRegions()->sync($regions);
    }



    /**
     * Alias to eloquent many-to-many relation's detach() method.
     *
     * @param mixed $region
     */
    public function detachRegion($region)
    {
        if (is_object($region)) {
            $region = $region->getKey();
        }
        if (is_array($region)) {
            $region = $region['id'];
        }
        $this->ferroliManagerRegions()->detach($region);
    }
}