<?php

namespace App\Http\Requests\V1;

use App\Models\Product_Review;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Product_Review::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'comment' => ['nullable', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ];
    }
}
