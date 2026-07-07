<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $user = Auth::user();

        $projects = Project::where('user_id', $user->id)
            ->with([
                'phases.tasks.progresses',
            ])
            ->latest()
            ->get();

        $totalActiveProjects =
            $projects
                ->where('progress_percentage', '<', 100)
                ->count();

        $totalCompletedProjects =
            $projects
                ->where('progress_percentage', 100)
                ->count();

        $totalInProgressProjects =
            $projects
                ->filter(function ($project) {

                    return
                        $project->progress_percentage > 0 &&
                        $project->progress_percentage < 100;

                })
                ->count();

        $projectTable =
            $projects
                ->filter(function ($project) {

                    return $project->progress_percentage < 100;

                })
                ->values();

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

        return response()->json([

            'total_active_projects' => $totalActiveProjects,

            'total_in_progress_projects' => $totalInProgressProjects,

            'total_completed_projects' => $totalCompletedProjects,

            'projects' => $projectTable,

            'overdue_tasks' => $overdueTasks,

            'upcoming_deadlines' => $upcomingTasks,

        ]);
    }
}