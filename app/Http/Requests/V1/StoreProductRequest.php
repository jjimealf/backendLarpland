<?php

namespace App\Http\Requests\V1;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Product::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string'],
            'descripcion' => ['required', 'string'],
            'precio' => ['required', 'numeric', 'min:0'],
            'cantidad' => ['required', 'integer', 'min:0'],
            'valoracion_total' => ['sometimes', 'numeric', 'min:0', 'max:5'],
            'imagen' => ['required', 'image', 'max:5120'],
            'categoria' => ['required', 'string', 'max:100'],
        ];
    }
}
