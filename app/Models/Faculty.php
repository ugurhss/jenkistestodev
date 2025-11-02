<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
use HasFactory;

    protected $fillable = ['university_id', 'name'];

    /**
     * Fakülte bir üniversiteye aittir
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Fakültenin birden fazla bölümü olabilir
     */
    public function departments()
    {
        return $this->hasMany(Department::class);
    }
}
