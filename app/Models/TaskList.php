<?php

namespace App\Models;

use App\Models\Traits\Archivable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class TaskList extends Model
{
    use Archivable;

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'parent_list');
    }

    public function openTasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'parent_list')->where('state', 'open');
    }

    public function taskListGroups(): HasMany
    {
        return $this->hasMany(TaskListGroup::class);
    }

    public function getData(array $additionalData = []): array
    {
        return [
            'id' => $this->id,
            'type' => $this::class,
            'title' => $this->title,
            'parentTaskListId' => $this->parent_task_list_id,
            'isArchived' => $this->isArchived(),
            ...$additionalData,
        ];
    }
}
