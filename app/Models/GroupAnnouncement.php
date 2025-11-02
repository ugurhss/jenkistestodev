<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupAnnouncement extends Model
{
    use HasFactory;

    /**
     * Tablo adı 'group_announcements'
     */
    protected $table = 'group_announcements';

    protected $fillable = [
        'group_id',
        'user_id',
        'title',
        'content',
    ];

    /**
     * Bu duyuru bir gruba aittir (belongsTo)
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Bu duyuru bir kullanıcı tarafından oluşturulmuştur (belongsTo)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
