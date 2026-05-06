<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'name',
        'path',
        'mime_type',
        'size',
    ];

    protected $appends = ['url'];

    public function getUrlAttribute(): string
    {
        return url('storage/'.$this->path);
    }

    // ✅ Auto delete file from disk when record deleted
    protected static function booted(): void
    {
        static::deleting(function (Media $media) {
            Storage::disk('public')->delete($media->path);
        });
    }
}
