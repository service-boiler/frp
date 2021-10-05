<?php

namespace ServiceBoiler\Prf\Site\Concerns;

trait AttachTests
{
    /**
     * Attach multiple tests to a user
     *
     * @param mixed $tests
     */
    public function attachTests(array $tests)
    {
        foreach ($tests as $test) {
            $this->attachTest($test);
        }
    }

    /**
     * Alias to eloquent many-to-many relation's attach() method.
     *
     * @param mixed $test
     */
    public function attachTest($test)
    {
        if (is_object($test)) {
            $test = $test->getKey();
        }
        if (is_array($test)) {
            $test = $test['id'];
        }
        $this->tests()->attach($test);
    }

    /**
     * Detach multiple tests from a user
     *
     * @param mixed $tests
     */
    public function detachTests(array $tests)
    {
        if (!$tests) {
            $tests = $this->tests()->get();
        }
        foreach ($tests as $test) {
            $this->detachTest($test);
        }
    }

    /**
     * @param $tests
     */
    public function syncTests($tests)
    {
        if (!is_array($tests)) {
            $tests = [$tests];
        }
        $this->tests()->sync($tests, true);
    }


    /**
     * Alias to eloquent many-to-many relation's detach() method.
     *
     * @param mixed $test
     */
    public function detachTest($test)
    {
        if (is_object($test)) {
            $test = $test->getKey();
        }
        if (is_array($test)) {
            $test = $test['id'];
        }
        $this->tests()->detach($test);
    }
}