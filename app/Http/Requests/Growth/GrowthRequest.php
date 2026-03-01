<?php

namespace App\Http\Requests\Growth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GrowthRequest
 *
 * Validates input for creating/updating growth stages.
 */
class GrowthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,mixed>
     */
    public function rules(): array
    {
        return [
            'growth_code' => ['nullable', 'string', 'max:20', 'regex:/^[A-Za-z0-9-]+$/'],
            'growth_name' => ['required', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
        ];
    }

    /**
     * Custom messages (optional).
     *
     * @return array<string,string>
     */
    public function messages(): array
    {
        return [
            'growth_name.required' => 'The growth name is required.',
            'growth_name.string' => 'The growth name must be a string.',
            'date.date' => 'Date must be a valid date.',
        ];
    }
}
