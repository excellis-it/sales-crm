<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;


    public function projectMilestones()
    {
        return $this->hasMany(ProjectMilestone::class);
    }

    public function projectTypes()
    {
        return $this->hasMany(ProjectType::class);
    }

    public function salesManager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
