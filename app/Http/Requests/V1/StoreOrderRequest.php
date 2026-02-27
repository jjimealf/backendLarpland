<?php

namespace App\Http\Requests\V1;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Order::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'estado' => ['sometimes', 'in:pendiente,procesando,completado'],
            'fecha_pedido' => ['sometimes', 'date'],
            'direccion_envio' => ['required', 'string', 'max:255'],
        ];
    }
}
