<?php

namespace App\Http\Controllers;

use App\Http\Requests\ToggleTaskProgressRequest;
use App\Models\Project;
use App\Models\Phase;
use App\Models\Task;
use App\Models\TaskProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TaskProgressController extends Controller
{
    /**
     * Display all progress records of a task.
     */
    public function index(
        Project $project,
        Phase $phase,
        Task $task
    ): JsonResponse {

        $project = Project::where('user_id', Auth::id())
            ->findOrFail($project->id);

        $phase = $project->phases()
            ->findOrFail($phase->id);

        $task = $phase->tasks()
            ->findOrFail($task->id);

        return response()->json(

            $task->progresses()
                ->orderBy('progress_date')
                ->get()

        );
    }

    /**
     * Toggle a progress day.
     */
    public function toggle(
        ToggleTaskProgressRequest $request,
        Project $project,
        Phase $phase,
        Task $task
    ): JsonResponse {

        $project = Project::where('user_id', Auth::id())
            ->findOrFail($project->id);

        $phase = $project->phases()
            ->findOrFail($phase->id);

        $task = $phase->tasks()
            ->findOrFail($task->id);

        $progress = $task->progresses()
            ->where('progress_date', $request->progress_date)
            ->first();

        if ($progress) {

            $progress->delete();

            return response()->json([

                'message' => 'Progress removed.',

            ]);

        }

        $progress = TaskProgress::create([

            'task_id' => $task->id,
            'progress_date' => $request->progress_date,

        ]);

        return response()->json([

            'message' => 'Progress added.',
            'data' => $progress,

        ], 201);
    }
}