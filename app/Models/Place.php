<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function absen()
    {
        return $this->hasMany(Absensi::class, 'id', 'id');
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'pivot_user_areas');
    }
}
