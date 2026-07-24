<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(): JsonResponse
    {
        $user = Auth::user();

        $overdueTasks = Task::whereHas('phase.project', function ($query) use ($user) {

                $query->where('user_id', $user->id);

            })
            ->where('is_completed', false)
            ->whereDate('end_date', '<', Carbon::today())
            ->with('phase.project')
            ->orderBy('end_date')
            ->get();

        $upcomingTasks = Task::whereHas('phase.project', function ($query) use ($user) {

                $query->where('user_id', $user->id);

            })
            ->where('is_completed', false)
            ->whereBetween(
                'end_date',
                [
                    Carbon::today(),
                    Carbon::today()->copy()->addDays(3),
                ]
            )
            ->with('phase.project')
            ->orderBy('end_date')
            ->get();

        $notifications = [];

        foreach ($overdueTasks as $task) {

            $daysOverdue = Carbon::parse($task->end_date)
                ->diffInDays(Carbon::today());

            $notifications[] = [

                'type' => 'overdue',

                'project_id' => $task->phase->project->id,

                'severity' => 'danger',

                'id' => 'overdue-' . $task->id,

                'task_name' => $task->task_name,

                'project_name' => $task->phase->project->project_name,

                'message' =>
                    'Task "' .
                    $task->task_name .
                    '" is ' .
                    $daysOverdue .
                    ' day' .
                    ($daysOverdue > 1 ? 's' : '') .
                    ' overdue.',

                'days' => $daysOverdue,

                'end_date' => $task->end_date,

            ];

        }

        foreach ($upcomingTasks as $task) {

            $daysRemaining = Carbon::today()
                ->diffInDays(Carbon::parse($task->end_date));

            $notifications[] = [

                'type' => 'upcoming',

                'project_id' => $task->phase->project->id,

                'severity' => 'warning',

                'id' => 'upcoming-' . $task->id,

                'task_name' => $task->task_name,

                'project_name' => $task->phase->project->project_name,

                'message' =>
                    'Task "' .
                    $task->task_name .
                    '" is due in ' .
                    $daysRemaining .
                    ' day' .
                    ($daysRemaining !== 1 ? 's' : '') .
                    '.',

                'days' => $daysRemaining,

                'end_date' => $task->end_date,

            ];

        }

        usort($notifications, function ($a, $b) {

            if ($a['type'] !== $b['type']) {
                return $a['type'] === 'overdue' ? -1 : 1;
            }

            return strtotime($a['end_date']) <=> strtotime($b['end_date']);

        });

        return response()->json($notifications);
    }
}
