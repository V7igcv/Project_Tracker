<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhaseRequest;
use App\Http\Requests\UpdatePhaseRequest;
use App\Models\Phase;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PhaseController extends Controller
{
    /**
     * Display all phases of a project.
     */
    public function index(Project $project): JsonResponse
    {
        $project = Project::where('user_id', Auth::id())
            ->findOrFail($project->id);

        return response()->json(

            $project->phases()
                ->with([
                    'tasks.progresses',
                ])
                ->orderBy('phase_number')
                ->get()

        );
    }

    /**
     * Store a new phase.
     */
    public function store(
        StorePhaseRequest $request,
        Project $project
    ): JsonResponse {

        $project = Project::where('user_id', Auth::id())
            ->findOrFail($project->id);

        $nextPhaseNumber =
            $project->phases()->max('phase_number') + 1;

        $phase = Phase::create([
            'project_id' => $project->id,
            'phase_number' => $nextPhaseNumber,
            'phase_name' => $request->phase_name,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Phase created successfully.',
            'data' => $phase,
        ], 201);
    }

    /**
     * Display one phase.
     */
    public function show(Project $project, Phase $phase): JsonResponse
    {
        $project = Project::where('user_id', Auth::id())
            ->findOrFail($project->id);

        $phase = $project->phases()
            ->findOrFail($phase->id);

        return response()->json(

            $phase->load([
                'tasks.progresses',
            ])

        );
    }

    /**
     * Update a phase.
     */
    public function update(
        UpdatePhaseRequest $request,
        Project $project,
        Phase $phase
    ): JsonResponse {

        $project = Project::where('user_id', Auth::id())
            ->findOrFail($project->id);

        $phase = $project->phases()
            ->findOrFail($phase->id);

        $phase->update([
            'phase_name' => $request->phase_name,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Phase updated successfully.',
            'data' => $phase,
        ]);
    }

    /**
     * Delete a phase.
     */
    public function destroy(
        Project $project,
        Phase $phase
    ): JsonResponse {

        $project = Project::where('user_id', Auth::id())
            ->findOrFail($project->id);

        $phase = $project->phases()
            ->findOrFail($phase->id);

        $phase->delete();

        return response()->json([
            'message' => 'Phase deleted successfully.',
        ]);
    }
}