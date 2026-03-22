<?php

namespace App\Models;

use App\Helpers\DateHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Task extends Model
{
    protected $casts = [
        'checklist' => 'array',
    ];

    public function parentList(): MorphTo
    {
        return $this->morphTo();
    }

    public function getData(array $additionalData = []): array
    {
        return [
            'id' => $this->id,
            'type' => $this::class,
            'title' => $this->title,
            'state' => $this->state,
            'plannedFor' => $this->planned_for,
            'deadline' => $this->deadline,
            'plannedForLabel' => DateHelper::getPlannedForLabel($this->planned_for),
            'deadlineLabel' => DateHelper::getDeadlineLabel($this->deadline),
            'checklist' => $this->checklist,
            'notes' => $this->notes,
            'parentListType' => $this->parent_list_type,
            'parentListId' => $this->parent_list_id,
            ...$additionalData,
        ];
    }
}
