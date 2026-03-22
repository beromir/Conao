<?php

use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\ClosedTasksController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskListController;
use App\Http\Controllers\TaskListGroupController;
use App\Http\Controllers\TodayController;
use App\Http\Controllers\UpcomingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return to_route('inbox');
    })->name('home');

    Route::get('/inbox', [InboxController::class, 'index'])->name('inbox');
    Route::get('/today', [TodayController::class, 'index'])->name('today');
    Route::get('/upcoming', [UpcomingController::class, 'index'])->name('upcoming');
    Route::get('/closed-tasks', [ClosedTasksController::class, 'index'])->name('closedTasks');
    Route::get('/archive', [ArchiveController::class, 'index'])->name('archive');

    Route::patch('/tasks/change-state', [TaskController::class, 'changeState'])->name('tasks.changeState');
    Route::patch('/tasks/move', [TaskController::class, 'move'])->name('tasks.move');
    Route::post('/archive', [ArchiveController::class, 'archive']);
    Route::post('/unarchive', [ArchiveController::class, 'unarchive']);

    Route::resource('tasks', TaskController::class);
    Route::resource('taskLists', TaskListController::class);
    Route::resource('taskListGroups', TaskListGroupController::class);

    Route::post('/task-lists/merge', [TaskListController::class, 'mergeTaskLists'])->name('taskLists.merge');
});

require __DIR__ . '/auth.php';
