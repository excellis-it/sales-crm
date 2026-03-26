<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upsale extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'upsale_project_type',
        'other_project_type',
        'upsale_value',
        'upsale_upfront',
        'upsale_currency',
        'upsale_payment_method',
        'upsale_date',
    ];

    protected $casts = [
        'upsale_project_type' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function upfrontMilestone()
    {
        return $this->hasOne(ProjectMilestone::class)->where('milestone_type', 'upsale_upfront');
    }

    public function milestones()
    {
        return $this->hasMany(ProjectMilestone::class)->where('milestone_type', 'upsale_milestone');
    }

    public function getProjectTypeLabelAttribute()
    {
        $types = $this->upsale_project_type ?? [];
        $labels = [];
        foreach ($types as $type) {
            if ($type === 'Other' && $this->other_project_type) {
                $labels[] = $this->other_project_type;
            } else {
                $labels[] = $type;
            }
        }
        return implode(', ', $labels);
    }
}
