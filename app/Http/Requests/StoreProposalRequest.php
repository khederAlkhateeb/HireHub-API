<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project;

class StoreProposalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        if (!$user) return false;

        $projectId = $this->route('project_id');
        $project = Project::find($projectId);

        if (!$project) return false;

        return
            $user->type === 'freelancer' &&
            $project->user_id !== $user->id &&
            $project->status === 'open' &&
            !\App\Models\Proposal::where('user_id', $user->id)
                ->where('project_id', $projectId)
                ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount'        => 'required|numeric|min:1',
            'delivery_days' => 'required|integer|min:1',
            'proposal'      => [
                'required',
                'string',
                'min:50',
                new \App\Rules\ProhibitedContent()
            ]
        ];
    }
}
