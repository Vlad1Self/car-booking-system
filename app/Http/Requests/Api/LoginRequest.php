<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\LoginData;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function getDTO(): LoginData
    {
        $data = $this->validated();
        $data['device_name'] = $data['device_name'] ?? substr($this->userAgent() ?? 'Unknown Device', 0, 255);

        return LoginData::from($data);
    }
}
