<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArchiveRequest;
use App\Models\TaskList;
use Inertia\Inertia;

class ArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('archive/Index', [
            'archivedTaskLists' => TaskList::orderBy('title')
                ->where('user_id', auth()->id())
                ->archived()
                ->get()
                ->transform(fn(TaskList $taskList) => $taskList->getData()),
        ]);
    }

    public function archive(ArchiveRequest $request)
    {
        $validated = $request->validated();

        $taskListId = $validated['taskListId'];

        $taskList = TaskList::find($taskListId);

        if (!$taskList) {
            return back();
        }

        $taskList->markAsArchived();

        return back();
    }

    public function unarchive(ArchiveRequest $request)
    {
        $validated = $request->validated();

        $taskListId = $validated['taskListId'];

        $taskList = TaskList::find($taskListId);

        if (!$taskList) {
            return back();
        }

        $taskList->unarchive();

        return back();
    }
}
