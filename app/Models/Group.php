<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    /**
     * Migration'daki sütun adlarıyla eşleşen fillable
     */
    protected $fillable = [
        'user_id',
        'city_id',
        'university_id',
        'faculty_id',
        'department_id',
        'class_models_id', // Düzeltildi
        'groups_name'      // Düzeltildi
    ];

    /**
     * Grubu oluşturan kullanıcı (user_id)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * 'class' rezerve kelime olduğu için 'classModel' olarak adlandırıldı.
     * Foreign key 'class_models_id' olarak düzeltildi.
     */
    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'class_models_id');
    }

    // --- YENİ İLİŞKİLER ---

    /**
     * Bir grubun birden fazla öğrencisi (User) olabilir (Many-to-Many)
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'group_user');
    }

    /**
     * Bir grubun birden fazla duyurusu olabilir (One-to-Many)
     */
    public function announcements()
    {
        return $this->hasMany(GroupAnnouncement::class);
    }
}
