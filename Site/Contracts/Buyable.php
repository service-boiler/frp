<?php

namespace ServiceBoiler\Prf\Site\Contracts;

interface Buyable
{
    function quantity();
    function weight();
    function subtotal();
}