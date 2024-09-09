<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust authorization logic if necessary
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'priority' => 'sometimes|in:low,medium,high', // Assuming priority values are low, medium, high
            'due_date' => 'sometimes|date_format:Y-m-d H:i:s', // Adjusted to match standard datetime format
            'status' => 'sometimes|required|string|in:pending,completed', // Assuming valid statuses are pending or completed
            'assigned_to' => 'nullable|exists:users,id',
            'created_by' => 'exists:users,id',
        ];
    }

    /**
     * Customize the validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.sometimes' => 'The title field is optional but should be a string if present.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'description.sometimes' => 'The description field is optional but should be a string if present.',
            'description.string' => 'The description must be a string.',
            'priority.sometimes' => 'The priority field is optional but should be one of the allowed values if present.',
            'priority.in' => 'The priority must be one of: low, medium, high.',
            'due_date.sometimes' => 'The due date field is optional but should be a valid date if present.',
            'due_date.date_format' => 'The due date does not match the required format Y-m-d H:i:s.',
            'status.sometimes' => 'The status field is optional but should be required and valid if present.',
            'status.in' => 'The status must be one of: pending, completed.',
            'assigned_to.exists' => 'The selected assigned user does not exist.',
            'created_by.exists' => 'The creator user does not exist.',
        ];
    }

    /**
     * Customize the attribute names for validation error messages.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'task title',
            'description' => 'task description',
            'priority' => 'task priority',
            'due_date' => 'due date',
            'status' => 'task status',
            'assigned_to' => 'assigned user',
            'created_by' => 'creator user',
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
