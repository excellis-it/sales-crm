<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BdmFollowup extends Model
{
    use HasFactory;

    protected $fillable = [
        'bdm_prospect_id',
        'bdm_project_id',
        'remark',
        'status',
        'last_call_status',
        'meeting_date',
        'next_followup_date',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
