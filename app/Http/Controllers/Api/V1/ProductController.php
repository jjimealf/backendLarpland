<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\StoreProductRequest;
use App\Http\Requests\V1\UpdateProductRequest;
use App\Http\Resources\V1\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Product::class);

        $query = Product::query();

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($builder) use ($search) {
                $builder->where('nombre', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if ($category = $request->string('categoria')->toString()) {
            $query->where('categoria', $category);
        }

        if (($minPrice = $request->input('min_price')) !== null) {
            $query->where('precio', '>=', (float) $minPrice);
        }

        if (($maxPrice = $request->input('max_price')) !== null) {
            $query->where('precio', '<=', (float) $maxPrice);
        }

        $products = $query->orderBy('id')->paginate($this->perPage($request))->withQueryString();

        return $this->paginated($products, ProductResource::class, 'Products fetched.');
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $data = $request->validated();
        $product = new Product($data);
        $product->imagen = $request->file('imagen')->store('public/img');
        $product->save();

        return $this->success(ProductResource::make($product), 'Product created.', 201);
    }

    public function show(Product $product): JsonResponse
    {
        $this->authorize('view', $product);

        return $this->success(ProductResource::make($product), 'Product fetched.');
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $data = $request->validated();
        $product->fill($data);

        if ($request->hasFile('imagen')) {
            if ($product->imagen) {
                Storage::delete($product->imagen);
            }
            $product->imagen = $request->file('imagen')->store('public/img');
        }

        $product->save();

        return $this->success(ProductResource::make($product->fresh()), 'Product updated.');
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->authorize('delete', $product);

        if ($product->imagen) {
            Storage::delete($product->imagen);
        }
        $product->delete();

        return $this->noContent();
    }
}
