<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'job_listing' => $this->whenLoaded(
                'jobListing', fn() => new JobListingResource($this->jobListing)),
            'candidate' => $this->whenLoaded(
                'candidate' , fn() => new UserResource($this->candidate)),
            'cover_letter' => $this->cover_letter,
            'resume_path' => $this->resume_path,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
