<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'first_name'       => 'sometimes|string|max:50',
            'last_name'        => 'sometimes|string|max:50',
            'bio'              => 'nullable|string',
            'experience_years' => 'sometimes|integer|min:0'
        ];
    }
}