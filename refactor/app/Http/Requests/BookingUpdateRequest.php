<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // You can implement your authorization logic here.
        // For example, check if the authenticated user has the necessary permission to update a booking.
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
            // Define the validation rules for the update request.
            // For example, you can add rules for each field that needs validation.
            // Here's an example using the 'required' and 'numeric' rules for the 'distance' field:
            'distance' => 'required|numeric',
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
            // Here's an example of a custom error message for the 'distance' field:
            'distance.required' => 'The distance field is required.',
            'distance.numeric' => 'The distance must be a number.',
            // Add custom error messages for other fields as needed.
        ];
    }
}
