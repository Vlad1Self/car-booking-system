<?php

namespace App\Http\Requests\Api;

use App\DTOs\CarSearchData;
use Illuminate\Foundation\Http\FormRequest;

class CarSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_time' => ['required', 'date', 'after_or_equal:now'],
            'end_time' => ['required', 'date', 'after:start_time'],
            'car_model_id' => ['nullable', 'integer', 'exists:car_models,id'],
            'comfort_category_id' => ['nullable', 'integer', 'exists:comfort_categories,id'],
            'driver_id' => ['nullable', 'integer', 'exists:drivers,id'],
        ];
    }

    public function getDTO(): CarSearchData
    {
        return CarSearchData::from($this->validated());
    }
}
