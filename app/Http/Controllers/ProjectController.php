<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display all projects.
     */
    public function index(): JsonResponse
    {
        $projects = Project::where('user_id', Auth::id())
            ->with([
                'phases.tasks.progresses'
            ])
            ->latest()
            ->get();

        return response()->json($projects);
    }

    /**
     * Store a new project.
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = Project::create([
            'user_id' => Auth::id(),
            'project_name' => $request->project_name,
            'description' => $request->description,
            'priority' => $request->priority,
        ]);

        return response()->json([
            'message' => 'Project created successfully.',
            'data' => $project,
        ], 201);
    }

    /**
     * Display one project.
     */
    public function show(Project $project): JsonResponse
    {
        $project = Project::where('user_id', Auth::id())
            ->findOrFail($project->id);

        return response()->json(

            $project->load([
                'phases.tasks.progresses',
            ])

        );
    }

    /**
     * Update a project.
     */
    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $project = Project::where('user_id', Auth::id())
            ->findOrFail($project->id);

        $project->update([
            'project_name' => $request->project_name,
            'description' => $request->description,
            'priority' => $request->priority,
        ]);

        return response()->json([
            'message' => 'Project updated successfully.',
            'data' => $project,
        ]);
    }

    /**
     * Delete a project.
     */
    public function destroy(Project $project): JsonResponse
    {
        $project = Project::where('user_id', Auth::id())
            ->findOrFail($project->id);

        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully.',
        ]);
    }
}