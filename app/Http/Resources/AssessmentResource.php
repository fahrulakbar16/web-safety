<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'materis' => $this->when(isset($this->materis), $this->materis),
            'exams' => $this->when(isset($this->exams), $this->exams),
            'all_materis_completed' => $this->when(isset($this->all_materis_completed), $this->all_materis_completed),
            'total_materis' => $this->when(isset($this->total_materis), $this->total_materis),
            'completed_materis' => $this->when(isset($this->completed_materis), $this->completed_materis),
        ];
    }
}

