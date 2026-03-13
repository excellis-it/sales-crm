<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenderProject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tender_name',
        'tender_id_ref_no',
        'department_org',
        'category',
        'category_title',
        'tender_value_lakhs',
        'emd',
        'delivery_date',
        'status',
        'l1_quoted_value',
        'excellis_it_quoted_price',
        'contact_authority_name',
        'contact_authority_phone',
        'contact_authority_email',
        'tender_user_id',
    ];

    public function milestones()
    {
        return $this->hasMany(ProjectMilestone::class, 'tender_project_id');
    }

    public function followups()
    {
        return $this->hasMany(TenderFollowup::class, 'tender_project_id');
    }

    public function tenderStatus()
    {
        return $this->belongsTo(TenderStatus::class, 'status');
    }

    public function tenderUser()
    {
        return $this->belongsTo(User::class, 'tender_user_id');
    }
}
