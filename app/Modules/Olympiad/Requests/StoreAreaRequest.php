<?php

namespace App\Modules\Olympiad\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAreaRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'name' => strtoupper(trim($this->name)),
        ]);
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    
    
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:area,name|max:50',
        ];
    }
    
    public function messages()
    {
        return [
            'name.required' => 'The area name is required.',
            'name.unique' => 'This area already exists.',
            'name.max' => 'The name must not exceed 50 characters.',
        ];
    }

}
