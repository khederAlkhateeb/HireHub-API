<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSkillsRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'skills'         => 'required|array',
            'skills.*.id'    => 'required|exists:skills,id',
            'skills.*.years' => 'required|integer|min:0'
        ];
    }


    public function getFormattedSkills(): array
    {
        $formatted = [];
        foreach ($this->skills as $skill) {
            $formatted[$skill['id']] = ['years_of_experience' => $skill['years']];
        }
        return $formatted;
    }
}