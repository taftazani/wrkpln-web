<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginTimeout extends Model
{
    use HasFactory;

    protected $fillable = [
        'timeout_hours',
    ];
}