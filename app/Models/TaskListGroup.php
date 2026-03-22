<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class TaskListGroup extends Model
{
    public function taskList(): BelongsTo {
        return $this->belongsTo(TaskList::class);
    }

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'parent_list');
    }

    public function openTasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'parent_list')->where('state', 'open');
    }

    public function getData(array $additionalData = []): array
    {
        return [
            'id' => $this->id,
            'type' => $this::class,
            'title' => $this->title,
            'taskListId' => $this->task_list_id,
            ...$additionalData,
        ];
    }
}
