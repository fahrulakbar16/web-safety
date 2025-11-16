<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'user_id'   => $this->user_id,
            'user'       => $this->whenLoaded('user', function () {
                return [
                    'id'       => $this->user->id,
                    'name'     => $this->user->name,
                    'email'    => $this->user->email,
                    'username' => $this->user->username,
                ];
            }),
            'company_id' => $this->company_id,
            'company'    => $this->whenLoaded('company', function () {
                return [
                    'id'   => $this->company->id,
                    'name' => $this->company->name,
                ];
            }),
            'name'        => $this->name,
            'alamat'     => $this->alamat,
            'no_hp'      => $this->no_hp,
            'no_ktp'     => $this->no_ktp,
            'no_sim'     => $this->no_sim,
            'foto_ktp'   => $this->foto_ktp ? url('storage/' . $this->foto_ktp) : null,
            'foto_sim'   => $this->foto_sim ? url('storage/' . $this->foto_sim) : null,
            'foto_diri'  => $this->foto_diri ? url('storage/' . $this->foto_diri) : null,
            'status'     => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

