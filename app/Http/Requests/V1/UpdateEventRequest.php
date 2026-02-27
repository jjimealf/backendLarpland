<?php

namespace App\Http\Requests\V1;

use App\Models\Roleplay_event;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        $event = $this->route('event');

        return $event instanceof Roleplay_event
            ? ($this->user()?->can('update', $event) ?? false)
            : false;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['sometimes', 'string'],
            'descripcion' => ['sometimes', 'string'],
            'fecha_inicio' => ['sometimes', 'date'],
            'fecha_fin' => ['sometimes', 'date'],
            'image' => ['sometimes', 'image', 'max:5120'],
        ];
    }
}
