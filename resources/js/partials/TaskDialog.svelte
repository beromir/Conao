<script lang="ts">
    import type { ChecklistTask, Task } from '@/types';
    import { router, useForm } from '@inertiajs/svelte';
    import { dispatchCustomEvent, join, refreshTaskLists } from '@/utils';
    import { Model } from '@/enums';
    import Dialog from '@/components/solior/Dialog.svelte';
    import Field from '@/components/solior/Field.svelte';
    import Label from '@/components/solior/Label.svelte';
    import Input from '@/components/solior/Input.svelte';
    import Button from '@/components/solior/Button.svelte';
    import Checkbox from '@/components/solior/Checkbox.svelte';
    import Textarea from '@/components/solior/Textarea.svelte';
    import FieldGroup from '@/components/solior/FieldGroup.svelte';
    import Trash from '@/icons/16/solid/Trash.svelte';
    import Pencil from '@/icons/16/solid/Pencil.svelte';
    import Plus from '@/icons/16/solid/Plus.svelte';
    import DialogFooter from '@/components/solior/DialogFooter.svelte';

    let dialog: Dialog;
    let isEditing = $state(false);
    let task = $state<Task | null>(null);
    let checklistTaskTitle = $state('');
    let checklistTaskIdToEdit = $state('');

    const form = useForm<{
        title: string;
        plannedFor: string;
        deadline: string;
        checklist: ChecklistTask[];
        notes: string;
        parentListId: string;
        parentListType: string;
    }>({
        title: '',
        plannedFor: '',
        deadline: '',
        checklist: [] as ChecklistTask[],
        notes: '',
        parentListId: '',
        parentListType: '',
    });

    function handleCreateTask(newTask: Task) {
        $form.parentListId = newTask.parentListId;
        $form.parentListType = newTask.parentListType;

        dialog.show();
    }

    function handleEditTask(editedTask: Task) {
        $form.title = editedTask.title;
        $form.plannedFor = editedTask.plannedFor ?? '';
        $form.deadline = editedTask.deadline ?? '';
        $form.checklist = editedTask.checklist ?? [];
        $form.notes = editedTask.notes ?? '';
        $form.parentListId = editedTask.parentListId;
        $form.parentListType = editedTask.parentListType;

        isEditing = true;
        task = editedTask;
        dialog.show();
    }

    function handleSelectTaskList() {
        dispatchCustomEvent('taskLists.select', {
            selected: $form.parentListId,
        });

        window.addEventListener(
            'taskLists.selected',
            (e) => {
                $form.parentListType = e.detail ? Model.TaskList : '';
                $form.parentListId = e.detail ?? '';
            },
            { once: true },
        );
    }

    function handleSubmit(e: Event) {
        e.preventDefault();

        if (isEditing && task) {
            $form.patch(route('tasks.update', task.id), {
                onSuccess: () => {
                    refreshTaskLists();
                    reset();
                },
            });

            return;
        }

        $form.post(route('tasks.store'), {
            onSuccess: () => {
                refreshTaskLists();
                reset();
            },
        });
    }

    function handleNewChecklistTaskButtonClick() {
        if (checklistTaskTitle.length < 2) {
            return;
        }

        $form.checklist = [
            ...$form.checklist,
            {
                id: crypto.randomUUID(),
                title: checklistTaskTitle,
                state: 'open',
            },
        ];

        checklistTaskTitle = '';
    }

    function handleChecklistTaskCheck(id: string) {
        $form.checklist = $form.checklist.map((checklistTask) => {
            return checklistTask.id === id
                ? {
                      ...checklistTask,
                      state: checklistTask.state === 'open' ? 'closed' : 'open',
                  }
                : checklistTask;
        });
    }

    function handleInitChecklistTaskEditing(checklistTask: ChecklistTask) {
        checklistTaskTitle = checklistTask.title;
        checklistTaskIdToEdit = checklistTask.id;
    }

    function handleEditChecklistTask() {
        if (checklistTaskTitle.length < 2) {
            return;
        }

        $form.checklist = $form.checklist.map((checklistTask) => {
            return checklistTask.id === checklistTaskIdToEdit ? { ...checklistTask, title: checklistTaskTitle } : checklistTask;
        });

        checklistTaskIdToEdit = '';
        checklistTaskTitle = '';
    }

    function handleDeleteChecklistTask() {
        $form.checklist = $form.checklist.filter((checklistTask) => checklistTask.id !== checklistTaskIdToEdit);

        checklistTaskIdToEdit = '';
        checklistTaskTitle = '';
    }

    function handleDeleteTask() {
        if (!task) return;

        router.delete(route('tasks.destroy', task.id), {
            onSuccess: () => {
                refreshTaskLists();
                reset();
            },
        });
    }

    function reset() {
        dialog.hide();

        $form.resetAndClearErrors();
        isEditing = false;
        checklistTaskIdToEdit = '';
        checklistTaskTitle = '';
        task = null;
    }
</script>

<svelte:window ontasks.create={(e) => handleCreateTask(e.detail)} ontasks.edit={(e) => handleEditTask(e.detail)} />

<Dialog bind:this={dialog} title={isEditing && task ? `Edit Task` : 'New Task'} cancel={reset}>
    <form onsubmit={handleSubmit}>
        <FieldGroup>
            <Field id="title">
                <Label>Title</Label>
                <Input type="text" bind:value={$form.title} required placeholder="Task title" />
                {$form.errors.title && $form.errors.title}
            </Field>

            <div class="grid grid-cols-2 gap-x-3">
                <Field id="planned-for">
                    <Label>Planned for</Label>
                    <Input type="date" bind:value={$form.plannedFor} />
                    {$form.errors.title && $form.errors.plannedFor}
                </Field>
                <Field id="deadline">
                    <Label>Deadline</Label>
                    <Input type="date" bind:value={$form.deadline} />
                    {$form.errors.title && $form.errors.deadline}
                </Field>
            </div>

            <Field id="parent-list">
                <Label>Parent list</Label>
                <Button onclick={handleSelectTaskList} class="w-full!">
                    {$form.parentListId ? '1 list selected' : 'Select list'}
                </Button>
                {$form.errors.parentListId && $form.errors.parentListId}
                {$form.errors.parentListType && $form.errors.parentListType}
            </Field>

            <Field id="checklist">
                <Label class="mb-3 block">Checklist</Label>

                {#if $form.checklist}
                    <div class="mb-4">
                        {#each $form.checklist as checklistTask (checklistTask.id)}
                            <div class="flex items-center">
                                <Checkbox
                                    onchange={() => handleChecklistTaskCheck(checklistTask.id)}
                                    checked={checklistTask.state === 'closed'}
                                    class={join(checklistTask.state === 'closed' ? 'opacity-60' : '')}
                                />
                                <button
                                    onclick={() => handleInitChecklistTaskEditing(checklistTask)}
                                    type="button"
                                    class={join('ml-2 dark:text-neutral-50', checklistTask.state === 'closed' ? 'opacity-60' : '')}
                                >
                                    {checklistTask.title}
                                </button>
                            </div>
                        {/each}
                    </div>
                {/if}

                <div class="flex items-center gap-x-3">
                    <Field id="task-title" class="flex-1">
                        <Label class="sr-only">Task title</Label>
                        <Input type="text" bind:value={checklistTaskTitle} autocomplete="off" placeholder="New task" class="mt-0!" />
                    </Field>

                    {#if checklistTaskIdToEdit}
                        <Button onclick={handleDeleteChecklistTask} title="Delete task">
                            <Trash />
                        </Button>
                        <Button onclick={handleEditChecklistTask}>
                            <Pencil />
                            Edit
                        </Button>
                    {:else}
                        <Button onclick={handleNewChecklistTaskButtonClick}>
                            <Plus />
                            Add
                        </Button>
                    {/if}
                </div>
            </Field>

            <Field id="notes">
                <Label>Notes</Label>
                <Textarea bind:value={$form.notes} rows={4} />
                {$form.errors.notes && $form.errors.notes}
            </Field>
        </FieldGroup>

        <DialogFooter>
            <Button onclick={handleDeleteTask} title="Delete task" class="mr-auto">
                <Trash />
            </Button>
            <Button type="submit" disabled={$form.processing}>
                {isEditing ? 'Edit' : 'Create'}
            </Button>
        </DialogFooter>
    </form>
</Dialog>
