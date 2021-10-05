<?php

namespace ServiceBoiler\Prf\Site\Contracts;

interface Addressable
{
    function path();

    function lang();

    function getKey();
}