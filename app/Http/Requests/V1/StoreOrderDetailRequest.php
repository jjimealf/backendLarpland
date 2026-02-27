<?php

namespace App\Http\Requests\V1;

use App\Models\Detail_Order;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Detail_Order::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'precio_unitario' => ['required', 'numeric', 'min:0'],
        ];
    }
}
