<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'project_name',
        'description',
        'priority',
    ];

    protected $appends = [
        'progress_percentage',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function phases()
    {
        return $this->hasMany(Phase::class)->orderBy('phase_number')->orderBy('id');
    }

    protected function progressPercentage(): Attribute
    {
        return Attribute::make(

            get: function () {

                $tasks = $this->phases
                    ->flatMap
                    ->tasks;

                $totalTasks = $tasks->count();

                if ($totalTasks === 0) {
                    return 0;
                }

                $completedTasks = $tasks
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