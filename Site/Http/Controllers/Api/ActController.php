<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Http\Resources\ActResource;
use ServiceBoiler\Prf\Site\Models\Act;

class ActController extends Controller
{

    /**
     * @param Act $act
     * @return ActResource
     */
    public function show(Act $act)
    {
        return new ActResource($act);
    }
}