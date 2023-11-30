<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;


    public function projectMilestones()
    {
        return $this->hasMany(ProjectMilestone::class);
    }

    public function projectTypes()
    {
        return $this->hasOne(ProjectType::class);
    }

    public function salesManager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function projectOpener()
    {
        return $this->belongsTo(User::class, 'project_opener');
    }

    public function projectCloser()
    {
        return $this->belongsTo(User::class, 'project_closer');
    }

    public function lastFollowUpType()
    {
        return $this->hasOne(Followup::class)->latest();
    }

    public function accountManager()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
