<?php

namespace App\Http\Requests\V1;

use App\Models\Product_Review;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        $review = $this->route('review');

        return $review instanceof Product_Review
            ? ($this->user()?->can('update', $review) ?? false)
            : false;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['sometimes', 'integer', 'exists:products,id'],
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'comment' => ['nullable', 'string'],
            'rating' => ['sometimes', 'integer', 'min:1', 'max:5'],
        ];
    }
}
