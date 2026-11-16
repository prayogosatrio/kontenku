<?php

namespace App\Http\Requests;

use App\Models\Post;

class StorePostRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'campaign_id' => 'required|integer|exists:campaigns,id',
            'platform_id' => 'required|integer|exists:platforms,id',
            'judul' => 'required|string|max:150',
            'caption' => 'required|string|max:2200',
            'format' => 'required|in:'.implode(',', Post::FORMATS),
            'tanggal_tayang' => 'required|date',
            // Status "tayang" tidak diisi lewat form, hanya lewat tombol "Tandai Tayang".
            'status' => 'required|in:draft,dijadwalkan',
        ];
    }

    public function attributes(): array
    {
        return [
            'campaign_id' => 'Campaign',
            'platform_id' => 'Platform',
            'judul' => 'Judul',
            'caption' => 'Caption',
            'format' => 'Format',
            'tanggal_tayang' => 'Tanggal tayang',
            'status' => 'Status',
        ];
    }
}
