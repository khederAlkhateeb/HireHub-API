<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|confirmed|min:8',
            'type'       => 'required|in:client,freelancer',
            'phone'      => 'required|string',
            'city_id'    => 'required|exists:cities,id'
        ];
    }
}