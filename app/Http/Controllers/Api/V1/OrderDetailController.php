<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\StoreOrderDetailRequest;
use App\Http\Requests\V1\UpdateOrderDetailRequest;
use App\Http\Resources\V1\OrderDetailResource;
use App\Models\Detail_Order;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderDetailController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Detail_Order::class);

        $query = Detail_Order::query()->with(['order.user', 'product']);
        $user = $request->user();

        if (!$user->isAdmin()) {
            $query->whereHas('order', function ($builder) use ($user) {
                $builder->where('user_id', $user->id);
            });
        }

        $details = $query->latest('id')->paginate($this->perPage($request))->withQueryString();

        return $this->paginated($details, OrderDetailResource::class, 'Order details fetched.');
    }

    public function store(StoreOrderDetailRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $request->user();

        if (
            !$user->isAdmin()
            && !Order::whereKey($data['order_id'])->where('user_id', $user->id)->exists()
        ) {
            return $this->error(
                'forbidden',
                'You can only add details to your own orders.',
                403
            );
        }

        $detail = Detail_Order::create($data);

        return $this->success(
            OrderDetailResource::make($detail->load(['order.user', 'product'])),
            'Order detail created.',
            201
        );
    }

    public function show(Detail_Order $orderDetail): JsonResponse
    {
        $orderDetail->loadMissing(['order.user', 'product']);
        $this->authorize('view', $orderDetail);

        return $this->success(OrderDetailResource::make($orderDetail), 'Order detail fetched.');
    }

    public function update(UpdateOrderDetailRequest $request, Detail_Order $orderDetail): JsonResponse
    {
        $data = $request->validated();
        $user = $request->user();

        if (
            isset($data['order_id'])
            && !$user->isAdmin()
            && !Order::whereKey($data['order_id'])->where('user_id', $user->id)->exists()
        ) {
            return $this->error(
                'forbidden',
                'You can only move details to your own orders.',
                403
            );
        }

        $orderDetail->update($data);

        return $this->success(
            OrderDetailResource::make($orderDetail->fresh()->load(['order.user', 'product'])),
            'Order detail updated.'
        );
    }

    public function destroy(Detail_Order $orderDetail): JsonResponse
    {
        $orderDetail->loadMissing('order');
        $this->authorize('delete', $orderDetail);

        $orderDetail->delete();

        return $this->noContent();
    }
}
