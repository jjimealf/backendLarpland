<?php

namespace App\Http\Requests\V1;

use App\Models\Event_registration;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $registration = $this->route('eventRegistration');

        return $registration instanceof Event_registration
            ? ($this->user()?->can('update', $registration) ?? false)
            : false;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'event_id' => ['sometimes', 'integer', 'exists:roleplay_events,id'],
        ];
    }
}
