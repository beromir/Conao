<script lang="ts">
    import type { TaskListGroup } from '@/types';
    import Dialog from '@/components/solior/Dialog.svelte';
    import { useForm } from '@inertiajs/svelte';
    import FieldGroup from '@/components/solior/FieldGroup.svelte';
    import DialogFooter from '@/components/solior/DialogFooter.svelte';
    import Input from '@/components/solior/Input.svelte';
    import Field from '@/components/solior/Field.svelte';
    import Button from '@/components/solior/Button.svelte';
    import Label from '@/components/solior/Label.svelte';

    let dialog: Dialog;
    let isEditing = $state(false);
    let taskListGroup: TaskListGroup | null = $state(null);

    const form = useForm<{
        title: string;
        taskListId: string;
    }>({
        title: '',
        taskListId: '',
    });

    function handleCreateTaskListGroup(taskListId: number) {
        form.taskListId = taskListId.toString();

        dialog.show();
    }

    function handleEditTaskListGroup(taskListGroupToEdit: TaskListGroup) {
        form.title = taskListGroupToEdit.title;
        form.taskListId = taskListGroupToEdit.taskListId.toString();

        isEditing = true;
        taskListGroup = taskListGroupToEdit;
        dialog.show();
    }

    function handleSubmit(e: Event) {
        e.preventDefault();

        if (isEditing) {
            form.patch(route('taskListGroups.update', taskListGroup?.id), {
                onSuccess: () => {
                    reset();
                },
            });

            return;
        }

        form.post(route('taskListGroups.store'), {
            onSuccess: () => {
                reset();
            },
        });
    }

    function reset() {
        dialog.hide();

        form.resetAndClearErrors();
        isEditing = false;
        taskListGroup = null;
    }
</script>

<svelte:window
    ontaskListGroups.create={(e) => handleCreateTaskListGroup(e.detail)}
    ontaskListGroups.edit={(e) => handleEditTaskListGroup(e.detail)}
/>

<Dialog bind:this={dialog} title={isEditing && taskListGroup ? `Edit Group` : 'New Group'} cancel={reset}>
    <form onsubmit={handleSubmit}>
        <FieldGroup>
            <Field id="title">
                <Label>Title</Label>
                <Input type="text" bind:value={form.title} required placeholder="Group title" invalid={!!form.errors.title} />
                {form.errors.title && form.errors.title}
            </Field>
        </FieldGroup>

        <DialogFooter>
            <Button type="submit" disabled={form.processing}>{isEditing ? 'Edit' : 'Create'}</Button>
        </DialogFooter>
    </form>
</Dialog>
