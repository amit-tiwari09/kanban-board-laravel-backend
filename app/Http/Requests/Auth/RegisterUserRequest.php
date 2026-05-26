<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email|max:100',
            'password' => 'required|confirmed|max:100|min:4',
            'phone_number' => 'nullable|integer|digits_between:7,20',
            'country_code' => 'nullable|string|max:10',
            'last_name' => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [

            // First name
            'first_name.required' => 'First name is required.',
            'first_name.string' => 'First name must be a valid string.',
            'first_name.max' => 'First name cannot exceed 100 characters.',

            // Email
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'email.max' => 'Email address cannot exceed 100 characters.',

            // Password
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 4 characters.',
            'password.max' => 'Password cannot exceed 100 characters.',

            // Phone Number
            'phone_number.integer' => 'Phone number must contain only numbers.',
            'phone_number.digits_between' => 'Phone number must be between 7 and 20 digits.',

            // Country Code
            'country_code.string' => 'Country code must be a valid string.',
            'country_code.max' => 'Country code cannot exceed 10 characters.',

            // Last Name
            'last_name.string' => 'Last name must be a valid string.',
            'last_name.max' => 'Last name cannot exceed 100 characters.',
        ];
    }
}
