<?php

namespace App\Http\Requests\V1;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        $order = $this->route('order');

        return $order instanceof Order
            ? ($this->user()?->can('update', $order) ?? false)
            : false;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'estado' => ['sometimes', 'in:pendiente,procesando,completado'],
            'fecha_pedido' => ['sometimes', 'date'],
            'direccion_envio' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
