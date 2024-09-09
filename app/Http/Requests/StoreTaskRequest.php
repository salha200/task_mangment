<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
           'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'priority' => 'required|string|in:low,medium,high',
        'due_date' => 'required|date_format:d-m-Y H:i', 
        'status' => 'required|string|in:pending,completed',
        'assigned_to' => 'nullable|exists:users,id',
        'created_by' => 'required|exists:users,id' 
        ];
    }
       /**
     * Customize the validation error messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The task title is required.',
            'priority.required' => 'The task priority is required.',
            'priority.in' => 'The priority must be one of the following values: low, medium, high.',
            'due_date.required' => 'The due date is required.',
            'due_date.date_format' => 'The due date must be in the format: d-m-Y H:i.',
            'status.required' => 'The task status is required.',
            'status.in' => 'The status must be either "pending" or "completed".',
            'assigned_to.exists' => 'The selected user for assignment does not exist.',
            'created_by.required' => 'The creator ID is required.',
            'created_by.exists' => 'The selected creator user does not exist.',
        ];
    }

    /**
     * Customize the attribute names for validation error messages.
     */
    public function attributes(): array
    {
        return [
            'title' => 'task title',
            'description' => 'task description',
            'priority' => 'task priority',
            'due_date' => 'due date',
            'status' => 'task status',
            'assigned_to' => 'assigned user ID',
            'created_by' => 'creator ID',
        ];
    }

   

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        // Return a JSON response if validation fails
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors(),
            ], 422)
        );
    }
    
}
