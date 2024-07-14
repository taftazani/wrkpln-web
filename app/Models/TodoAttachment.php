<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoAttachment extends Model
{
    use HasFactory;
    protected $fillable = ['pict', 'detail'];
    public function todo()
    {
        return $this->belongsToMany(Todo::class, 'pivot_todo_attachments');
    }
}
