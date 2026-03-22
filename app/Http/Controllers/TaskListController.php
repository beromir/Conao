<?php

namespace App\Http\Controllers;

use App\Http\Requests\MergeTaskListsRequest;
use App\Http\Requests\StoreTaskListRequest;
use App\Http\Requests\UpdateTaskListRequest;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\TaskListGroup;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class TaskListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskListRequest $request)
    {
        $validated = $request->validated();

        $taskList = new TaskList();

        $taskList->title = $validated['title'];
        $taskList->user_id = auth()->id();
        $taskList->parent_task_list_id = $validated['parentTaskListId'];

        $taskList->save();

        return Redirect::route('taskLists.show', $taskList);
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskList $taskList)
    {
        return Inertia::render('taskLists/Show', [
            'taskList' => $taskList->getData(),
            'tasks' => $taskList->tasks()
                ->orderBy('created_at', 'desc')
                ->where('user_id', auth()->id())
                ->get()
                ->transform(fn(Task $task) => $task->getData()),
            'taskListGroups' => $taskList->taskListGroups()
                ->orderBy('title')
                ->where('user_id', auth()->id())
                ->get()
                ->transform(fn(TaskListGroup $taskListGroup) => $taskListGroup->getData([
                    'tasks' => $taskListGroup->tasks()
                        ->orderBy('created_at', 'desc')
                        ->where('user_id', auth()->id())
                        ->get()
                        ->transform(fn(Task $task) => $task->getData()),
                ])),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskList $taskList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskListRequest $request, TaskList $taskList)
    {
        $validated = $request->validated();

        $taskList->title = $validated['title'];
        $taskList->parent_task_list_id = $validated['parentTaskListId'];

        $taskList->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskList $taskList)
    {
        foreach ($taskList->tasks as $task) {
            $task->parentList()->dissociate();
            $task->save();
        }

        $taskList->delete();

        return Redirect::route('inbox');
    }

    public function mergeTaskLists(MergeTaskListsRequest $request)
    {
        $validated = $request->validated();

        $sourceTaskListId = $validated['sourceTaskListId'];
        $targetTaskListId = $validated['targetTaskListId'];

        $sourceTaskList = TaskList::find($sourceTaskListId);
        $targetTaskList = TaskList::find($targetTaskListId);

        if (!$sourceTaskList || !$targetTaskList) {
            return back();
        }

        // Move tasks
        $tasks = $sourceTaskList->tasks;

        /** @var Task $task */
        foreach ($tasks as $task) {
            $task->parentList()->associate($targetTaskList);

            $task->save();
        }

//        // Move sections
//        $sections = $sourceList->sections;
//
//        /** @var Section $section */
//        foreach ($sections as $section) {
//            $section->parentList()->associate($targetList);
//
//            $section->save();
//        }

        $sourceTaskList->delete();

        return to_route('taskLists.show', $targetTaskListId);
    }
}
