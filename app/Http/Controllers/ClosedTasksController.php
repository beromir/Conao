<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class ClosedTasksController extends Controller
{
    public function index()
    {
        $tasks = Task::orderBy('updated_at', 'desc')
            ->where('user_id', auth()->id())
            ->where('state', 'closed')
            ->get()
            ->transform(fn(Task $task) => $task->getData([
                'parentListTitle' => $task->parentList?->title,
                'updatedAt' => $task->updated_at,
            ]));

        $yesterday = Carbon::yesterday();
        $lastWeekStart = Carbon::now()->subDays(7)->startOfDay();
        $lastMonthStart = Carbon::now()->subMonth()->startOfDay();

        $groupedTasks = $tasks->groupBy(function ($task) use ($yesterday, $lastWeekStart, $lastMonthStart) {
            $updatedAt = Carbon::parse($task['updatedAt']);

            if ($updatedAt->isToday()) {
                return 'Today';
            } elseif ($updatedAt->isYesterday()) {
                return 'Yesterday';
            } elseif ($updatedAt->between($lastWeekStart, $yesterday)) {
                return 'Last Week';
            } elseif ($updatedAt->between($lastMonthStart, $lastWeekStart)) {
                return 'Last Month';
            } else {
                return 'Later';
            }
        });

        return Inertia::render('closedTasks/Index', [
            'groupedTasks' => $groupedTasks,
        ]);
    }
}
