<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Phase;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display all tasks of a phase.
     */
    public function index(
        Project $project,
        Phase $phase
    ): JsonResponse {

        $project = Project::where('user_id', Auth::id())
            ->findOrFail($project->id);

        $phase = $project->phases()
            ->findOrFail($phase->id);

        return response()->json(

            $phase->tasks()
                ->with('progresses')
                ->orderBy('start_date')
                ->get()

        );
    }

    /**
     * Store a task.
     */
    public function store(
        StoreTaskRequest $request,
        Project $project,
        Phase $phase
    ): JsonResponse {

        $project = Project::where('user_id', Auth::id())
            ->findOrFail($project->id);

        $phase = $project->phases()
            ->findOrFail($phase->id);

        $task = Task::create([

            'phase_id' => $phase->id,
            'task_name' => $request->task_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_completed' => false,

        ]);

        $task->load('progresses');

        return response()->json([
            'message' => 'Task created successfully.',
            'data' => $task,
        ], 201);
    }

    /**
     * Display one task.
     */
    public function show(
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

        return response()->json($task);
    }

    /**
     * Update a task.
     */
    public function update(
        UpdateTaskRequest $request,
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

        $task->update([

            'task_name' => $request->task_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_completed' => $request->boolean('is_completed'),
            'completed_at' => $request->boolean('is_completed')
                ? now()
                : null,

        ]);

        $task->load('progresses');

        return response()->json([

            'message' => 'Task updated successfully.',
            'data' => $task,

        ]);
    }

    /**
     * Delete a task.
     */
    public function destroy(
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

        $task->delete();

        return response()->json([

            'message' => 'Task deleted successfully.',

        ]);
    }
}