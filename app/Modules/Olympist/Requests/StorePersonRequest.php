<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePersonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'names' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u',
            'surnames' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u',
            'ci_person' => 'required|integer|unique:persona,ci_person',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email:rfc,dns|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|max:100',
            'birthdate' => 'required|date|before_or_equal:today',
        ];
    }

    public function messages(): array
    {
        return [
            'names.required' => 'The "first names" field is required.',
            'names.string' => 'The "first names" field must be text.',
            'names.max' => 'The "first names" field cannot exceed 100 characters.',
            'names.regex' => 'The "first names" field can only contain letters and spaces.',
            
            'surnames.required' => 'The "last names" field is required.',
            'surnames.string' => 'The "last names" field must be text.',
            'surnames.max' => 'The "last names" field cannot exceed 100 characters.',
            'surnames.regex' => 'The "last names" field can only contain letters and spaces.',
            
            'ci_person.required' => 'The "ID number" field is required.',
            'ci_person.integer' => 'The "ID number" field must be an integer.',
            'ci_person.unique' => 'This "ID number" is already registered in our database.',
            
            'phone.string' => 'The "phone number" field must be text.',
            'phone.max' => 'The "phone number" field cannot exceed 20 characters.',
            
            'email.required' => 'The "email" field is required.',
            'email.regex' => 'The email format is invalid.',
            'email.max' => 'The "email" field cannot exceed 100 characters.',

            'birthdate.required' => 'The "birthdate" field is required.',
            'birthdate.date' => 'The "birthdate" must be a valid date.',
            'birthdate.before_or_equal' => 'The "birthdate" cannot be in the future.',
        ];
    }
}