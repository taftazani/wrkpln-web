<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'postal_code',
        'user_count',
        'company_code',
        'package_type_id',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function packageType()
    {
        return $this->belongsTo(PackageType::class, 'package_type_id');
    }
}
