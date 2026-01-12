<?php

namespace App\Http\Requests\Api;

use App\DTOs\RegisterData;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'position_id' => ['required', 'integer', 'exists:positions,id'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function getDTO(): RegisterData
    {
        $data = $this->validated();
        $data['device_name'] = $data['device_name'] ?? substr($this->userAgent() ?? 'Unknown Device', 0, 255);

        return RegisterData::from($data);
    }
}
