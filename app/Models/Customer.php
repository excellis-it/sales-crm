<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public function projects()
    {
        return $this->hasMany(Project::class, 'customer_id', 'id');
    }

    public function bdmProjects()
    {
        return $this->hasMany(BdmProject::class, 'customer_id', 'id');
    }

    public function upsales()
    {
        return $this->hasManyThrough(Upsale::class, Project::class, 'customer_id', 'project_id');
    }
}
