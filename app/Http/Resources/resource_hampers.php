<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class resource_hampers extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Id' => $this->Id,
            'Nama_Hampers' => $this->Nama_Hampers,
            'Harga' => $this->Harga,
            'Gambar' => $this->Gambar,
        ];
    }
}
