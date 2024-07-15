<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkUploadOrganization extends Model
{
    use HasFactory;
    protected $fillable = [
        'daftar_no_urut',
        'nama_excel_upload',
        'data_berhasil',
        'data_gagal',
        'status',
    ];

    public function detailBulkUploadOrganizations()
    {
        return $this->hasMany(DetilBulkUploadOrganization::class);
    }
}
