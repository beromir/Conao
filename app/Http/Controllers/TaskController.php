<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\TaskListGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
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
    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();

        $task = Task::make();

        $task->title = $validated['title'];
        $task->user_id = auth()->id();
        $task->planned_for = $validated['plannedFor'] ?? null;
        $task->deadline = $validated['deadline'] ?? null;
        $task->checklist = empty($validated['checklist']) ? null : $validated['checklist'];
        $task->notes = $validated['notes'] ?? null;

        $parentListId = $validated['parentListId'];
        $parentListType = $validated['parentListType'];

        if ($parentListId && $parentListType) {
            $list = $parentListType::find($parentListId);

            $task->parentList()->associate($list);
        } else {
            $task->parentList()->dissociate();
        }

        $task->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validated = $request->validated();

        $task->title = $validated['title'];
        $task->planned_for = $validated['plannedFor'];
        $task->deadline = $validated['deadline'];
        $task->checklist = empty($validated['checklist']) ? null : $validated['checklist'];
        $task->notes = $validated['notes'] ?? null;

        $parentListId = $validated['parentListId'] ?? null;
        $parentListType = $validated['parentListType'] ?? null;

        if ($parentListId && $parentListType) {
            $list = $parentListType::find($parentListId);

            $task->parentList()->associate($list);
        } else {
            $task->parentList()->dissociate();
        }

        $task->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
    }

    public function changeState(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:App\Models\Task,id',
            'state' => ['required',Rule::in(['open', 'closed'])]
        ]);

        $id = $validated['id'];
        $state = $validated['state'];

        $task = Task::find($id);

        $task->state = $state;

        $task->save();
    }

    public function move(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'exists:App\Models\Task,id'],
            'targetType' => ['required', 'string', Rule::in([TaskList::class, TaskListGroup::class])],
            'targetId' => ['required', 'integer'],
        ]);

        $task = Task::find($validated['id']);
        $targetList = $validated['targetType']::find($validated['targetId']);

        if (!$task || !$targetList) {
            return back();
        }

        $task->parentList()->associate($targetList);

        $task->save();
    }
}
