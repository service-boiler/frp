<?php

namespace ServiceBoiler\Prf\Site\Contracts;

interface SingleImageable
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function image();

    /**
     * @return bool
     */
    function save();

    /**
     * @return string
     */
    function imageStorage();
}