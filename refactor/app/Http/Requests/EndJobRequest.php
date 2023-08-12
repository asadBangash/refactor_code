<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EndJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // You can implement your authorization logic here.
        // For example, check if the authenticated user has the necessary permission to accept a job.
        // If yes, return true; otherwise, return false.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // Define the validation rules for the accept job request.
            // For example, you can add rules for each field that needs validation.
            'job_id' => 'required|integer|exists:jobs,id',
            // Add other validation rules for other fields as needed.
        ];
    }

    /**
     * Get custom messages for validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // Customize validation error messages if needed.
            // Here's an example of a custom error message for the 'job_id' field:
            'job_id.required' => 'The job ID field is required.',
            'job_id.integer' => 'The job ID must be an integer.',
            'job_id.exists' => 'The selected job ID is invalid.',
            // Add custom error messages for other fields as needed.
        ];
    }
}
