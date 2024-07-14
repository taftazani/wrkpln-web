<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    public function place()
    {
        return $this->hasOne(Place::class, 'id', 'id_place');
    }

    public function absen_attachment()
    {
        return $this->belongsToMany(AbsenAttachment::class, 'pivot_absen_attachments');
    }
}
