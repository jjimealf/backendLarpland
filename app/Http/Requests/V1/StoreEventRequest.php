<?php

namespace App\Http\Requests\V1;

use App\Models\Roleplay_event;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Roleplay_event::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string'],
            'descripcion' => ['required', 'string'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'image' => ['sometimes', 'image', 'max:5120'],
        ];
    }
}
