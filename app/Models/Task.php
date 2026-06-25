<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'phase_id',
        'task_name',
        'start_date',
        'end_date',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }

    public function progress()
    {
        return $this->hasMany(TaskProgress::class);
    }
}