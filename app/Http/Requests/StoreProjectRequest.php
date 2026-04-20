<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->type === 'client';;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:25|max:150',
            'description' => [
                'required',
                'string',
                'min:100',
                new \App\Rules\ProhibitedContent()
            ],
            'budget_type' => 'required|in:fixed,hourly',
            'budget' => [
                'required',
                'numeric',
                ...($this->budget_type === 'hourly'
                    ? ['min:5', 'max:500']
                    : ['min:10']
                ),
            ],
            'deadline' => 'required|date|after:today',
            'tags' => 'required|array|min:1|max:5',
            'tags.*' => 'exists:tags,id',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'title' => strip_tags($this->title),
            'description' => trim($this->description),
        ]);
    }
}
