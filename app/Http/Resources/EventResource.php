<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'image'       => $this->image ? url('storage/' . $this->image) : null,
            'status'      => $this->status,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            'event_materis' => $this->whenLoaded('eventMateris', function () {
                return $this->eventMateris->map(function ($materi) {
                    return [
                        'id'          => $materi->id,
                        'urutan'      => $materi->urutan,
                        'name'        => $materi->name,
                        'file'        => $materi->file ? url('storage/' . $materi->file) : null,
                        'type'        => $materi->type,
                        'description' => $materi->description,
                    ];
                });
            }),
            'exams' => $this->whenLoaded('exams', function () {
                return $this->exams->map(function ($exam) {
                    return [
                        'id'            => $exam->id,
                        'name'          => $exam->name,
                        'description'   => $exam->description,
                        'durasi'        => $exam->durasi,
                        'jumlah_soal'   => $exam->jumlah_soal,
                        'minimal_score' => $exam->minimal_score,
                    ];
                });
            }),
        ];
    }
}

