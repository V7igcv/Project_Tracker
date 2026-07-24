<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskProgressController;
use App\Http\Controllers\DashboardController;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->get('/', function () {
    return Inertia::render('Auth/Login', [
        'canResetPassword' => Route::has('password.request'),
        'status' => session('status'),
    ]);
});

/*
|--------------------------------------------------------------------------
| Pages (Inertia)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/projects', function () {
    $projects = Project::where('user_id', Auth::id())
        ->with([
            'phases.tasks.progresses',
        ])
        ->latest()
        ->get();

    return Inertia::render('Projects', [
        'projects' => $projects,
    ]);
})->middleware(['auth', 'verified'])->name('projects');

Route::get('/projects/{project}', function ($project) {

    // Current project (with everything needed by ProjectPhases.vue)
    $project = Project::where('user_id', Auth::id())
        ->with([
            'phases.tasks.progresses',
        ])
        ->findOrFail($project);

    // Lightweight list for the Header search
    $projects = Project::where('user_id', Auth::id())
        ->select('id', 'project_name')
        ->orderBy('project_name')
        ->get();

    return Inertia::render('ProjectPhases', [
        'project' => $project,
        'projects' => $projects,
    ]);

})->middleware(['auth'])->name('projects.phases');

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

/*
|--------------------------------------------------------------------------
| Project API
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('/api/projects')
    ->group(function () {

        //Project Controller routes
        Route::get('/', [ProjectController::class, 'index'])
            ->name('projects.index');

        Route::post('/', [ProjectController::class, 'store'])
            ->name('projects.store');

        Route::get('/{project}', [ProjectController::class, 'show'])
            ->name('projects.show');

        Route::put('/{project}', [ProjectController::class, 'update'])
            ->name('projects.update');

        Route::delete('/{project}', [ProjectController::class, 'destroy'])
            ->name('projects.destroy');

        //Phase Controller routes
        Route::get(
            '/{project}/phases',
            [PhaseController::class, 'index']
        )->name('phases.index');

        Route::post(
            '/{project}/phases',
            [PhaseController::class, 'store']
        )->name('phases.store');

        Route::get(
            '/{project}/phases/{phase}',
            [PhaseController::class, 'show']
        )->name('phases.show');

        Route::put(
            '/{project}/phases/{phase}',
            [PhaseController::class, 'update']
        )->name('phases.update');

        Route::delete(
            '/{project}/phases/{phase}',
            [PhaseController::class, 'destroy']
        )->name('phases.destroy');

        //Task Controller routes

        Route::get(
            '/{project}/phases/{phase}/tasks',
            [TaskController::class, 'index']
        )->name('tasks.index');

        Route::post(
            '/{project}/phases/{phase}/tasks',
            [TaskController::class, 'store']
        )->name('tasks.store');

        Route::get(
            '/{project}/phases/{phase}/tasks/{task}',
            [TaskController::class, 'show']
        )->name('tasks.show');

        Route::put(
            '/{project}/phases/{phase}/tasks/{task}',
            [TaskController::class, 'update']
        )->name('tasks.update');

        Route::delete(
            '/{project}/phases/{phase}/tasks/{task}',
            [TaskController::class, 'destroy']
        )->name('tasks.destroy');

        // Task Progress Controller routes

        Route::get(
            '/{project}/phases/{phase}/tasks/{task}/progresses',
            [TaskProgressController::class, 'index']
        )->name('progresses.index');

        Route::post(
            '/{project}/phases/{phase}/tasks/{task}/progresses/toggle',
            [TaskProgressController::class, 'toggle']
        )->name('progresses.toggle');
    });

Route::middleware('auth')
    ->prefix('/api/dashboard')
    ->group(function () {

        Route::get(
            '/',
            [DashboardController::class, 'index']
        )->name('dashboard.index');

    });

require __DIR__.'/auth.php';