<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Bir şehirde birden fazla üniversite olabilir
     */
    public function universities()
    {
        return $this->hasMany(University::class);
    }
}
