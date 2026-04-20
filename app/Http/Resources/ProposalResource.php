<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProposalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'amount' => $this->amount,
            'duration' => $this->duration,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at->diffForHumans(),
        ];

        if ($this->status === 'accepted') {
            $data['contact_info'] = $this->project->client->email;
            $data['next_steps'] = "The client will contact you shortly.";
        }

        if ($this->status === 'rejected') {
            $data['rejection_reason'] = $this->rejection_reason;
        }

        return $data;
    }
}
