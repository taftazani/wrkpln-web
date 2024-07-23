<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'price', 'status'
    ];
    public function companies()
    {
        return $this->hasMany(Company::class);
    }
}
