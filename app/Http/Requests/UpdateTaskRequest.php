<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date|after_or_equal:today',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:low,medium,high',
        ];
    }

    public function messages()
    {
        return [
            'due_date.required' => 'The due date field is required',
            'due_date.date' => 'The due date must be a valid date',
            'due_date.after_or_equal' => 'Due date must be today or in the future',
            'status.in' => 'Invalid status value',
            'priority.in' => 'Invalid priority value',
        ];
    }
}
