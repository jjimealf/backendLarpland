<?php

namespace App\Http\Requests\V1;

use App\Models\Detail_Order;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        $detail = $this->route('orderDetail');

        return $detail instanceof Detail_Order
            ? ($this->user()?->can('update', $detail) ?? false)
            : false;
    }

    public function rules(): array
    {
        return [
            'order_id' => ['sometimes', 'integer', 'exists:orders,id'],
            'product_id' => ['sometimes', 'integer', 'exists:products,id'],
            'cantidad' => ['sometimes', 'integer', 'min:1'],
            'precio_unitario' => ['sometimes', 'numeric', 'min:0'],
        ];
    }
}
