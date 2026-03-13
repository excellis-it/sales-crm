<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BdmProjectDocument extends Model
{
    use HasFactory;

    protected $table = 'bdm_project_documents';

    public function project()
    {
        return $this->belongsTo(BdmProject::class, 'bdm_project_id');
    }
}
