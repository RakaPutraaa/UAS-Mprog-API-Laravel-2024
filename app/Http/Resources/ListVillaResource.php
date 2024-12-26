<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListVillaResource extends JsonResource
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
            'id_user' => $this->id_user,
            'id_kabupaten' => $this->id_kabupaten,
            'nama_villa' => $this->nama_villa,
            'deskripsi_villa' => $this->deskripsi_villa,
            'harga_villa' => $this->harga_villa,
            'foto_villa' => $this->foto_villa,
            'lokasi_villa' =>$this->lokasi_villa,
            'date' => date_format($this->created_at, "Y/m/d H:i:s")
        ];
    }
}
