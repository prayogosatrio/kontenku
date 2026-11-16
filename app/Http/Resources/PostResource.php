<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'judul' => $this->judul,
            'caption' => $this->caption,
            'format' => $this->format,
            'tanggal_tayang' => $this->tanggal_tayang?->toDateString(),
            'tanggal_ditayangkan' => $this->tanggal_ditayangkan?->toDateString(),
            'status' => $this->status,
            'platform' => [
                'id' => $this->platform?->id,
                'nama_platform' => $this->platform?->nama_platform,
            ],
            'campaign' => [
                'id' => $this->campaign?->id,
                'nama_campaign' => $this->campaign?->nama_campaign,
            ],
            'klien' => [
                'id' => $this->campaign?->client?->id,
                'nama_brand' => $this->campaign?->client?->nama_brand,
            ],
        ];
    }
}
