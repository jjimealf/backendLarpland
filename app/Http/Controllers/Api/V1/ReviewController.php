<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\StoreProductReviewRequest;
use App\Http\Requests\V1\UpdateProductReviewRequest;
use App\Http\Resources\V1\ProductReviewResource;
use App\Models\Product;
use App\Models\Product_Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Product_Review::class);

        $query = Product_Review::query()->with(['user', 'product']);

        if (($productId = $request->input('product_id')) !== null) {
            $query->where('product_id', (int) $productId);
        }

        $reviews = $query->latest('id')->paginate($this->perPage($request))->withQueryString();

        return $this->paginated($reviews, ProductReviewResource::class, 'Reviews fetched.');
    }

    public function byProduct(Request $request, Product $product): JsonResponse
    {
        $this->authorize('viewAny', Product_Review::class);

        $reviews = Product_Review::query()
            ->with(['user', 'product'])
            ->where('product_id', $product->id)
            ->latest('id')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return $this->paginated(
            $reviews,
            ProductReviewResource::class,
            'Product reviews fetched.'
        );
    }

    public function store(StoreProductReviewRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $request->user();

        if (!$user->isAdmin()) {
            $data['user_id'] = $user->id;
        } else {
            $data['user_id'] = $data['user_id'] ?? $user->id;
        }

        $duplicate = Product_Review::query()
            ->where('user_id', $data['user_id'])
            ->where('product_id', $data['product_id'])
            ->exists();

        if ($duplicate) {
            return $this->error(
                'duplicate_review',
                'A review already exists for this user and product.',
                422
            );
        }

        $review = Product_Review::create($data);

        return $this->success(
            ProductReviewResource::make($review->load(['user', 'product'])),
            'Review created.',
            201
        );
    }

    public function show(Product_Review $review): JsonResponse
    {
        $this->authorize('view', $review);

        return $this->success(
            ProductReviewResource::make($review->load(['user', 'product'])),
            'Review fetched.'
        );
    }

    public function update(UpdateProductReviewRequest $request, Product_Review $review): JsonResponse
    {
        $data = $request->validated();

        if (!$request->user()->isAdmin()) {
            unset($data['user_id']);
        }

        $nextUserId = $data['user_id'] ?? $review->user_id;
        $nextProductId = $data['product_id'] ?? $review->product_id;

        $duplicate = Product_Review::query()
            ->where('id', '!=', $review->id)
            ->where('user_id', $nextUserId)
            ->where('product_id', $nextProductId)
            ->exists();

        if ($duplicate) {
            return $this->error(
                'duplicate_review',
                'A review already exists for this user and product.',
                422
            );
        }

        $review->update($data);

        return $this->success(
            ProductReviewResource::make($review->fresh()->load(['user', 'product'])),
            'Review updated.'
        );
    }

    public function destroy(Product_Review $review): JsonResponse
    {
        $this->authorize('delete', $review);

        $review->delete();

        return $this->noContent();
    }
}
