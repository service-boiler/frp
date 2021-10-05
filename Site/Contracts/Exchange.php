<?php

namespace ServiceBoiler\Prf\Site\Contracts;

interface Exchange
{
    function get($id): array;

    function all(): array;
}