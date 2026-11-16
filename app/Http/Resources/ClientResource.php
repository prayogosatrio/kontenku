<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_brand' => $this->nama_brand,
            'industri' => $this->industri,
            'nama_kontak' => $this->nama_kontak,
            'email' => $this->email,
            'nomor_telepon' => $this->nomor_telepon,
            'status' => $this->status,
            'jumlah_campaign' => $this->campaigns_count,
        ];
    }
}
