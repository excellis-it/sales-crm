<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMilestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'tender_project_id',
        'bdm_project_id',
        'milestone_name',
        'milestone_value',
        'payment_status',
        'payment_date',
        'milestone_comment',
        'payment_mode',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function bdmProject()
    {
        return $this->belongsTo(BdmProject::class, 'bdm_project_id');
    }

    public function tenderProject()
    {
        return $this->belongsTo(TenderProject::class, 'tender_project_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
