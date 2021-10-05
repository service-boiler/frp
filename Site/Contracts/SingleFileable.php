<?php

namespace ServiceBoiler\Prf\Site\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface SingleFileable
{
    /**
     * @return BelongsTo
     */
    function file();

    /**
     * @return bool
     */
    function save();

    /**
     * @return string
     */
    function fileStorage();
}