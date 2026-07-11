<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Task extends Model
{
    protected $appends = [
        'due_human',
        'overdue_human',
    ];

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

    public function progresses()
    {
        return $this->hasMany(TaskProgress::class);
    }

    protected function dueHuman(): Attribute
    {
        return Attribute::make(

            get: function () {

                if ($this->is_completed) {
                    return null;
                }

                $today = Carbon::today();

                $days = $today->diffInDays($this->end_date, false);

                return match (true) {

                    $days < 0 => null,

                    $days === 0 => 'Today',

                    $days === 1 => 'Tomorrow',

                    default => "{$days} days",

                };

            }

        );
    }

    protected function overdueHuman(): Attribute
    {
        return Attribute::make(

            get: function () {

                if ($this->is_completed) {
                    return null;
                }

                $today = Carbon::today();

                if ($today->lte($this->end_date)) {
                    return null;
                }

                $days = $this->end_date->diffInDays($today);

                if ($days >= 30) {

                    $months = floor($days / 30);

                    return "{$months} month" .
                        ($months > 1 ? 's' : '') .
                        " overdue";
                }

                return "{$days} day" .
                    ($days > 1 ? 's' : '') .
                    " overdue";

            }

        );
    }

}