<?php

namespace App\Http\Requests\V1;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $target = $this->route('user');

        return $target instanceof User
            ? ($this->user()?->can('update', $target) ?? false)
            : false;
    }

    public function rules(): array
    {
        $target = $this->route('user');
        $targetId = $target instanceof User ? $target->id : null;

        return [
            'name' => ['sometimes', 'string', 'max:25'],
            'email' => ['sometimes', 'email', 'max:100', Rule::unique('users', 'email')->ignore($targetId)],
            'password' => ['sometimes', 'string', 'min:8'],
            'rol' => ['sometimes', 'integer', 'in:0,1'],
        ];
    }
}
