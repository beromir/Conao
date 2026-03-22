<?php

namespace App\Http\Requests;

use App\Models\Section;
use App\Models\TaskList;
use App\Models\TaskListGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:2'],
            'plannedFor' => ['nullable', 'date'],
            'deadline' => ['nullable', 'date'],
            'checklist' => ['nullable', 'array'],
            'checklist.*.id' => ['required', 'uuid', 'string'],
            'checklist.*.title' => ['required', 'string'],
            'checklist.*.state' => ['required', 'string', 'in:open,closed'],
            'notes' => ['nullable', 'string'],
            'parentListId' => ['nullable', 'integer'],
            'parentListType' => ['nullable', 'string', Rule::in([TaskList::class, TaskListGroup::class])],
        ];
    }
}
