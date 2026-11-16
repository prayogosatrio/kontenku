<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    public const FORMATS = ['feed', 'reels', 'story', 'video', 'artikel'];

    public const STATUSES = ['draft', 'dijadwalkan', 'tayang'];

    protected $fillable = [
        'campaign_id', 'platform_id', 'judul', 'caption', 'format',
        'tanggal_tayang', 'tanggal_ditayangkan', 'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_tayang' => 'date',
            'tanggal_ditayangkan' => 'date',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }
}
