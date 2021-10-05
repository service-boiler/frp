<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Http\Resources\OrderResource;
use ServiceBoiler\Prf\Site\Models\Order;

class OrderController extends Controller
{

    /**
     * @param Order $order
     * @return OrderResource
     */
    public function show(Order $order)
    {
        return new OrderResource($order);
    }
}