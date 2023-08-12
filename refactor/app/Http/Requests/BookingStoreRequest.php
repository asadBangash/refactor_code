<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // You can implement your authorization logic here.
        // For example, check if the authenticated user has the necessary permission to create a booking.
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
            // Define the validation rules for the store request.
            // For example, you can add rules for each field that needs validation.
            'field1' => 'required|string|max:255',
            'field2' => 'numeric|min:0',
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
            // Here's an example of a custom error message for the 'field1' field:
            'field1.required' => 'The Field 1 field is required.',
            'field1.string' => 'The Field 1 must be a string.',
            'field1.max' => 'The Field 1 may not be greater than :max characters.',
            // Add custom error messages for other fields as needed.
        ];
    }
}
