<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\StoreOrderRequest;
use App\Http\Requests\V1\UpdateOrderRequest;
use App\Http\Resources\V1\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Order::class);

        $query = Order::query()->with('user');
        $user = $request->user();

        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        } elseif (($userId = $request->input('user_id')) !== null) {
            $query->where('user_id', (int) $userId);
        }

        if ($status = $request->string('estado')->toString()) {
            $query->where('estado', $status);
        }

        $orders = $query->latest('id')->paginate($this->perPage($request))->withQueryString();

        return $this->paginated($orders, OrderResource::class, 'Orders fetched.');
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $request->user();

        if (!$user->isAdmin()) {
            $data['user_id'] = $user->id;
        } else {
            $data['user_id'] = $data['user_id'] ?? $user->id;
        }

        $data['fecha_pedido'] = $data['fecha_pedido'] ?? now();

        $order = Order::create($data);

        return $this->success(
            OrderResource::make($order->load('user')),
            'Order created.',
            201
        );
    }

    public function show(Order $order): JsonResponse
    {
        $this->authorize('view', $order);

        return $this->success(
            OrderResource::make($order->load('user')),
            'Order fetched.'
        );
    }

    public function update(UpdateOrderRequest $request, Order $order): JsonResponse
    {
        $data = $request->validated();

        if (!$request->user()->isAdmin()) {
            unset($data['user_id']);
        }

        $order->update($data);

        return $this->success(
            OrderResource::make($order->fresh()->load('user')),
            'Order updated.'
        );
    }

    public function destroy(Order $order): JsonResponse
    {
        $this->authorize('delete', $order);

        $order->delete();

        return $this->noContent();
    }
}
