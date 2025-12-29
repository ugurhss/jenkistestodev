<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GroupAnnouncementAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_announcement_id',
        'original_name',
        'path',
        'mime_type',
        'type',
        'size',
    ];

    protected $appends = [
        'url',
    ];

    public function announcement()
    {
        return $this->belongsTo(GroupAnnouncement::class, 'group_announcement_id');
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }
}
