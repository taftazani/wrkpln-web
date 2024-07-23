<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisAktifitas extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode_jenis_aktifitas', 'nama_jenis_aktifitas', 'flag_tab_menu_personal',
        'batas_waktu_kunjungan', 'batas_maksimal_umur_progress', 'status'
    ];
}
