<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'class_models';
    protected $fillable = ['department_id', 'name'];

    /**
     * Sınıf bir bölüme aittir
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
