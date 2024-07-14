<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    public function absen()
    {
        return $this->hasOne(Absensi::class, 'id', 'id_absen');
    }

    public function izin()
    {
        return $this->hasOne(Izin::class, 'id', 'id_izin');
    }

    public function lembur()
    {
        return $this->hasOne(Lembur::class, 'id', 'id_lembur');
    }
}
