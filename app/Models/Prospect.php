<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prospect extends Model
{
    use HasFactory;
    

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transferTakenBy()
    {
        return $this->belongsTo(User::class, 'transfer_token_by');
    }
}
