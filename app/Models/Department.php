<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['faculty_id', 'name'];

    /**
     * Bölüm bir fakülteye aittir
     */
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Bölümün birden fazla sınıfı olabilir
     */
    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }
}
