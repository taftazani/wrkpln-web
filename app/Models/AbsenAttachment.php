<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenAttachment extends Model
{
    use HasFactory;
    protected $fillable = ['pict', 'detail'];
    public function absensi()
    {
        return $this->belongsToMany(Absensi::class, 'pivot_absen_attachments');
    }
}
