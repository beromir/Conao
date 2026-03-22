<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;

class InboxController extends Controller
{
    public function index()
    {
        return Inertia::render('inbox/Index', [
            'tasks' => Task::orderBy('created_at', 'desc')
                ->where('user_id', auth()->id())
                ->whereNull('parent_list_id')
                ->whereNull('parent_list_type')
                ->get()
                ->transform(fn(Task $task) => $task->getData())
        ]);
    }
}
