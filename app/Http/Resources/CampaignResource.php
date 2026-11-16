<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_campaign' => $this->nama_campaign,
            'tujuan' => $this->tujuan,
            'tanggal_mulai' => $this->tanggal_mulai?->toDateString(),
            'tanggal_selesai' => $this->tanggal_selesai?->toDateString(),
            'status' => $this->status,
            'klien' => [
                'id' => $this->client?->id,
                'nama_brand' => $this->client?->nama_brand,
            ],
            // Relasi user ditampilkan sebagai "pic"; kolom sensitif seperti password tidak ikut terkirim.
            'pic' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
            ],
            'posts' => $this->posts->map(fn ($post) => [
                'id' => $post->id,
                'judul' => $post->judul,
                'platform' => $post->platform?->nama_platform,
                'tanggal_tayang' => $post->tanggal_tayang?->toDateString(),
                'status' => $post->status,
            ]),
        ];
    }
}
