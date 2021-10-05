<?php

namespace ServiceBoiler\Prf\Site\Contracts;

use Illuminate\Database\Eloquent\Relations\morphMany;

interface Fileable
{
    /**
     * @return morphMany
     */
    function files();
}