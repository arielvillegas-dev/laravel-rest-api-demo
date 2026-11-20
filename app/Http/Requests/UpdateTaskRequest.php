<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'min:3', 'max:140'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['sometimes', Rule::in(['todo', 'in_progress', 'done'])],
            'priority' => ['sometimes', Rule::in(['low', 'medium', 'high'])],
            'due_date' => ['nullable', 'date_format:Y-m-d'],
        ];
    }
}
