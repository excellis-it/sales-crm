<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BdmProspect extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bdm_prospects';

    protected $fillable = [
        'user_id',
        'report_to',
        'client_name',
        'business_name',
        'client_email',
        'client_phone',
        'business_address',
        'source',
        'website',
        'status',
        'followup_date',
        'followup_time',
        'meeting_date',
        'sale_date',
        'upfront_value',
        'payment_mode',
        'comments',
        'price_quote',
        'offered_for',
        'transfer_token_by',
        'category',
        'designation',
        'added_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transferTakenBy()
    {
        return $this->belongsTo(User::class, 'transfer_token_by');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
