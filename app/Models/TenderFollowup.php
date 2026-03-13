<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenderFollowup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tender_project_id',
        'comment',
        'user_id',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tenderProject()
    {
        return $this->belongsTo(TenderProject::class, 'tender_project_id');
    }
}
