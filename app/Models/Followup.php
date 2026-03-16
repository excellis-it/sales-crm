<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Followup extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'prospect_id',
        'user_id',
        'followup_type',
        'followup_subject',
        'followup_description',
        'followup_date',
        'next_followup_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
