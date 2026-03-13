<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BdmProject extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bdm_projects';

    public function projectMilestones()
    {
        return $this->hasMany(ProjectMilestone::class, 'bdm_project_id');
    }

    public function projectTypes()
    {
        return $this->hasMany(BdmProjectType::class, 'bdm_project_id');
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

    public function accountManager()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function projectDocuments()
    {
        return $this->hasMany(BdmProjectDocument::class, 'bdm_project_id');
    }
}
