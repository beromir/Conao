<script lang="ts">
    import Dialog from '@/components/solior/Dialog.svelte';
    import type { TaskList } from '@/types';
    import { useForm } from '@inertiajs/svelte';
    import { dispatchCustomEvent, refreshTaskLists } from '@/utils';
    import DialogFooter from '@/components/solior/DialogFooter.svelte';
    import Button from '@/components/solior/Button.svelte';
    import FieldGroup from '@/components/solior/FieldGroup.svelte';
    import Field from '@/components/solior/Field.svelte';
    import Label from '@/components/solior/Label.svelte';
    import Input from '@/components/solior/Input.svelte';

    let dialog: Dialog;
    let isEditing = $state(false);
    let taskList = $state<TaskList | null>(null);

    const form = useForm<{
        title: string;
        parentTaskListId: number | null;
    }>({
        title: '',
        parentTaskListId: null,
    });

    function handleCreateTaskList() {
        dialog.show();
    }

    function handleEditTaskList(taskListToEdit: TaskList) {
        $form.title = taskListToEdit.title;
        $form.parentTaskListId = taskListToEdit.parentTaskListId;

        isEditing = true;
        taskList = taskListToEdit;

        dialog.show();
    }

    function handleSelectTaskList() {
        dispatchCustomEvent('taskLists.select', {
            selected: $form.parentTaskListId,
            disabled: taskList?.id,
            options: {
                modalTitle: 'Select List',
            },
        });

        window.addEventListener(
            'taskLists.selected',
            (e) => {
                $form.parentTaskListId = e.detail ?? '';
            },
            { once: true },
        );
    }

    function handleSubmit(e: Event) {
        e.preventDefault();

        if (isEditing && taskList) {
            $form.patch(route('taskLists.update', taskList.id), {
                onSuccess: () => {
                    refreshTaskLists();
                    reset();
                },
            });

            return;
        }

        $form.post(route('taskLists.store'), {
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
        taskList = null;
    }
</script>

<svelte:window ontaskLists.create={handleCreateTaskList} ontaskLists.edit={(e) => handleEditTaskList(e.detail)} />

<Dialog bind:this={dialog} title={isEditing && taskList ? `Edit List` : 'New List'} cancel={reset}>
    <form onsubmit={handleSubmit}>
        <FieldGroup>
            <Field id="title">
                <Label>Title</Label>
                <Input type="text" bind:value={$form.title} required placeholder="List title" invalid={!!$form.errors.title} />
                {$form.errors.title && $form.errors.title}
            </Field>
            <Field id="parent-list">
                <Label>Parent list</Label>
                <Button onclick={handleSelectTaskList} class="w-full!">
                    {$form.parentTaskListId ? '1 list selected' : 'Select list'}
                </Button>
                {$form.errors.parentTaskListId && $form.errors.parentTaskListId}
            </Field>
        </FieldGroup>

        <DialogFooter>
            <Button type="submit" disabled={$form.processing}>{isEditing ? 'Edit' : 'Create'}</Button>
        </DialogFooter>
    </form>
</Dialog>
