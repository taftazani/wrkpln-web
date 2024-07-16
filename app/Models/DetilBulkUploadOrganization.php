<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetilBulkUploadOrganization extends Model
{
    use HasFactory;

    protected $fillable = [
        'baris_kesalahan',
        'penjelasan',
        'bulk_upload_organizations_id'
    ];

    public function bulkUploadOrganizations()
    {
        return $this->belongsTo(BulkUploadOrganization::class);
    }
}
