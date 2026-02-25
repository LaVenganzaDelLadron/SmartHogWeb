<?php

namespace App\Http\Requests\Pen;

use Illuminate\Foundation\Http\FormRequest;

class PenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>|string>
     */
    public function rules(): array
    {
        return [
            'pen_code' => ['required', 'string', 'max:20', 'regex:/^[A-Za-z0-9-]+$/'],
            'pen_name' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'string', 'in:available,occupied,maintenance'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'date' => ['required', 'date'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'pen_code.required' => 'Pen code is required.',
            'pen_name.required' => 'Pen name is required.',
            'capacity.required' => 'Capacity is required.',
            'capacity.integer' => 'Capacity must be a whole number.',
            'capacity.min' => 'Capacity must be at least 1.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be available, occupied, or maintenance.',
            'date.required' => 'Date is required.',
            'date.date' => 'Date must be a valid date.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'pen_code' => is_string($this->input('pen_code')) ? trim($this->input('pen_code')) : $this->input('pen_code'),
            'pen_name' => is_string($this->input('pen_name')) ? trim($this->input('pen_name')) : $this->input('pen_name'),
            'status' => is_string($this->input('status')) ? strtolower(trim($this->input('status'))) : $this->input('status'),
            'notes' => is_string($this->input('notes')) ? trim($this->input('notes')) : $this->input('notes'),
        ]);
    }
}