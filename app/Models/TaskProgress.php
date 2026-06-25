<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskProgress extends Model
{
    protected $fillable = [
        'task_id',
        'progress_date',
    ];

    protected $casts = [
        'progress_date' => 'date',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}