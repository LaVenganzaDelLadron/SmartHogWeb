<?php

namespace App\Http\Requests\Batch;

use Illuminate\Foundation\Http\FormRequest;

class BatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'batch_name' => ['required', 'string', 'max:120'],
            'pig_count' => ['required', 'integer', 'min:1'],
            'current_age_days' => ['required', 'integer', 'min:0'],
            'avg_weight' => ['required', 'numeric', 'min:0.1'],
            'assigned_pen' => ['required', 'string', 'max:50'],
            'growth_stage' => ['required', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:500'],
            'health_notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'batch_name.required' => 'Batch name is required.',
            'pig_count.required' => 'Number of pigs is required.',
            'current_age_days.required' => 'Current age is required.',
            'avg_weight.required' => 'Average weight is required.',
            'assigned_pen.required' => 'Assigned pen is required.',
            'growth_stage.required' => 'Growth stage is required.',
        ];
    }
}
