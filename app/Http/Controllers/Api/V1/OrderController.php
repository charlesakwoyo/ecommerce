<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    /**
     * List the authenticated user's orders, most recent first.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $orders = Order::query()
            ->forUser($request->user())
            ->latest()
            ->paginate(10);

        return OrderResource::collection($orders);
    }

    /**
     * Show one of the authenticated user's orders.
     */
    public function show(Order $order): OrderResource
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items.product.images');

        return new OrderResource($order);
    }
}
