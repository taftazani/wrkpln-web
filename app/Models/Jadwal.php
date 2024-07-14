<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function shift()
    {
        return $this->hasOne(Shift::class, 'id', 'id_shift');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    public function place()
    {
        return $this->hasOne(Place::class, 'id', 'id_place');
    }
}
