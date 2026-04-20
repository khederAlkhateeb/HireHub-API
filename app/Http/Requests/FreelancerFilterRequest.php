<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FreelancerFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:50',
            'city_id' => 'nullable|integer|exists:cities,id',
            'is_verified' => 'nullable|boolean',
            'min_experience' => 'nullable|integer|min:0',
            'sort_by' => 'nullable|in:first_name,last_name,created_at,rating',
            'order' => 'nullable|in:asc,desc',
        ];
    }
}
