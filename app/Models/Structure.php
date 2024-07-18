<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Structure extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'organization_id', 'function_id', 'level_structure_id', 'parent_id', 'cost_center', 'plan_man_power', 'actual_man_power', 'position', 'sort_order'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function function()
    {
        return $this->belongsTo(Functions::class);
    }

    public function levelStructure()
    {
        return $this->belongsTo(LevelStructure::class);
    }

    public function children()
    {
        return $this->hasMany(Structure::class, 'parent_id')->orderBy('sort_order');
    }

    public function parent()
    {
        return $this->belongsTo(Structure::class, 'parent_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'structure_user', 'structure_id', 'user_id');
    }
}