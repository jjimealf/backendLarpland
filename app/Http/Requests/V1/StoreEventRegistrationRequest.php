<?php

namespace App\Http\Requests\V1;

use App\Models\Event_registration;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Event_registration::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'event_id' => ['required', 'integer', 'exists:roleplay_events,id'],
        ];
    }
}
