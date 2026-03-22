<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class TodayController extends Controller
{
    public function index()
    {
        return Inertia::render('today/Index', [
            'tasks' => Task::oldest('planned_for')
                ->oldest('deadline')
                ->where('user_id', auth()->id())
                ->where(function (Builder $query) {
                    $query->whereDate('planned_for', '<=', today())
                        ->orWhereDate('deadline', '<=', today());
                })
                ->get()
                ->transform(fn(Task $task) => $task->getData(['parentListTitle' => $task->parentList?->title]))
        ]);
    }
}
