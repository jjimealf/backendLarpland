<?php

namespace App\Http\Requests\V1;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        $product = $this->route('product');

        return $product instanceof Product
            ? ($this->user()?->can('update', $product) ?? false)
            : false;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['sometimes', 'string'],
            'descripcion' => ['sometimes', 'string'],
            'precio' => ['sometimes', 'numeric', 'min:0'],
            'cantidad' => ['sometimes', 'integer', 'min:0'],
            'valoracion_total' => ['sometimes', 'numeric', 'min:0', 'max:5'],
            'imagen' => ['sometimes', 'image', 'max:5120'],
            'categoria' => ['sometimes', 'string', 'max:100'],
        ];
    }
}
