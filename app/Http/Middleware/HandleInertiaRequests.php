<?php

namespace App\Http\Middleware;

use App\Models\TaskList;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $shared = parent::share($request);
        $shared['appName'] = Inertia::once(fn() => config('app.name'));

        if (auth()->hasUser()) {
            $shared['taskLists'] = Inertia::once(fn() => TaskList::orderBy('title')
                ->where('user_id', auth()->id())
                ->notArchived()
                ->withCount('openTasks')
                ->with(['taskListGroups' => function ($query) {
                    $query->withCount('openTasks');
                }])
                ->get()
                ->transform(fn(TaskList $taskList) => $taskList->getData(['tasksCount' => $taskList->open_tasks_count + $taskList->taskListGroups->sum('open_tasks_count')])));
        }

        return $shared;
    }
}
