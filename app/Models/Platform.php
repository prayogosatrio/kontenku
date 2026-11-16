<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Platform extends Model
{
    protected $fillable = ['nama_platform', 'deskripsi'];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
