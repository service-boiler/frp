<?php

namespace ServiceBoiler\Prf\Site\Concerns;

trait AttachVideos
{
    /**
     * Attach multiple videos to a user
     *
     * @param mixed $videos
     */
    public function attachVideos(array $videos)
    {
        foreach ($videos as $video) {
            $this->attachVideo($video);
        }
    }

    /**
     * Alias to eloquent many-to-many relation's attach() method.
     *
     * @param mixed $video
     */
    public function attachVideo($video)
    {
        if (is_object($video)) {
            $video = $video->getKey();
        }
        if (is_array($video)) {
            $video = $video['id'];
        }
        $this->videos()->attach($video);
    }

    /**
     * Detach multiple videos from a user
     *
     * @param mixed $videos
     */
    public function detachVideos(array $videos)
    {
        if (!$videos) {
            $videos = $this->videos()->get();
        }
        foreach ($videos as $video) {
            $this->detachVideo($video);
        }
    }

    /**
     * @param $videos
     */
    public function syncVideos($videos)
    {
        if (!is_array($videos)) {
            $videos = [$videos];
        }
        $this->videos()->sync($videos, true);
    }


    /**
     * Alias to eloquent many-to-many relation's detach() method.
     *
     * @param mixed $video
     */
    public function detachVideo($video)
    {
        if (is_object($video)) {
            $video = $video->getKey();
        }
        if (is_array($video)) {
            $video = $video['id'];
        }
        $this->videos()->detach($video);
    }
}