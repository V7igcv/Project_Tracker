<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Phase extends Model
{
    protected $fillable = [
        'project_id',
        'phase_number',
        'phase_name',
        'description',
    ];

    protected $appends = [
        'progress_percentage',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    protected function progressPercentage(): Attribute
    {
        return Attribute::make(

            get: function () {

                $totalTasks = $this->tasks->count();

                if ($totalTasks === 0) {
                    return 0;
                }

                $completedTasks = $this->tasks
                    ->where('is_completed', true)
                    ->count();

                return round(
                    ($completedTasks / $totalTasks) * 100,
                    2
                );

            }

        );
    }
}