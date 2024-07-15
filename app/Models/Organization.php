<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'daftar_no_urut',
        'kode_organization',
        'nama_organization',
        'penjelasan',
        'status',
    ];
}
