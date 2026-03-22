<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskListGroupRequest;
use App\Http\Requests\UpdateTaskListGroupRequest;
use App\Models\Task;
use App\Models\TaskListGroup;

class TaskListGroupController extends Controller
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
    public function store(StoreTaskListGroupRequest $request)
    {
        $validated = $request->validated();

        $taskListGroup = TaskListGroup::make();

        $taskListGroup->title = $validated['title'];
        $taskListGroup->user_id = auth()->id();
        $taskListGroup->task_list_id = $validated['taskListId'];

        $taskListGroup->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskListGroup $taskListGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskListGroup $taskListGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskListGroupRequest $request, TaskListGroup $taskListGroup)
    {
        $validated = $request->validated();

        $taskListGroup->title = $validated['title'];
        $taskListGroup->task_list_id = $validated['taskListId'];

        $taskListGroup->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskListGroup $taskListGroup)
    {
        $taskList = $taskListGroup->taskList;

        /** @var Task $task */
        foreach ($taskListGroup->tasks as $task) {
            $task->parentList()->associate($taskList);

            $task->save();
        }

        $taskListGroup->delete();
    }

    public function move(MoveSectionRequest $request)
    {
        $validated = $request->validated();

        $section = Section::find($validated['id']);

        $parentListId = $validated['parentListId'];
        $parentListType = $validated['parentListType'];

        $list = $parentListType::find($parentListId);

        $section->parentList()->associate($list);

        $section->save();

        $listType = match ($parentListType) {
            Group::class => 'groups',
            Project::class => 'projects',
        };

        return to_route("$listType.show", $parentListId);
    }
}
