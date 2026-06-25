<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'project_name',
        'description',
        'priority',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function phases()
    {
        return $this->hasMany(Phase::class);
    }
}