<script lang="ts">
    import Dialog from '@/components/solior/Dialog.svelte';
    import type { TaskList } from '@/types';
    import { dispatchCustomEvent, join } from '@/utils';
    import DialogFooter from '@/components/solior/DialogFooter.svelte';
    import Button from '@/components/solior/Button.svelte';
    import NoSymbol from '@/icons/16/solid/NoSymbol.svelte';
    import { page } from '@inertiajs/svelte';

    let dialog: Dialog;
    let selectedTaskListId: number | null = $state(null);
    let disabledTaskListId: number | null = $state(null);
    let modalTitle: string = $state('Select List');
    let submitButtonTitle: string = $state('Select');
    let showDeselectButton: boolean = $state(true);

    let taskLists: TaskList[] = $derived($page.props.taskLists);

    async function handleInitSelectTaskList({
        selected,
        disabled,
        options,
    }: {
        selected: number;
        disabled: number;
        options?: {
            modalTitle: string;
            submitButtonTitle: string;
            showDeselectButton: boolean;
        };
    }) {
        disabledTaskListId = disabled;
        modalTitle = options?.modalTitle ?? modalTitle;
        submitButtonTitle = options?.submitButtonTitle ?? submitButtonTitle;
        showDeselectButton = options?.showDeselectButton ?? showDeselectButton;

        if (taskLists) {
            selectedTaskListId = taskLists.find((taskList) => taskList.id === selected)?.id ?? null;

            dialog.show();
        }
    }

    function handleSelectTaskList(taskListId: number) {
        selectedTaskListId = taskListId;
    }

    function handleSubmit() {
        dispatchCustomEvent('taskLists.selected', selectedTaskListId);
        reset();
    }

    function handleCancel() {
        dispatchCustomEvent('taskLists.selected', false);
        reset();
    }

    function reset() {
        dialog.hide();

        selectedTaskListId = null;
        disabledTaskListId = null;
        modalTitle = 'Select List';
        submitButtonTitle = 'Select';
        showDeselectButton = true;
    }
</script>

<svelte:window ontaskLists.select={(e) => handleInitSelectTaskList(e.detail)} />

<Dialog bind:this={dialog} title={modalTitle} cancel={reset}>
    <div class="space-y-0.5">
        {#if taskLists.length}
            {#each taskLists.filter((taskList) => !taskList.parentTaskListId) as taskList (taskList.id)}
                {@render taskListItem(taskList)}
            {/each}
        {/if}
    </div>

    {#if showDeselectButton && selectedTaskListId}
        <Button onclick={() => (selectedTaskListId = null)} plain class="mt-3 w-full!">
            <NoSymbol />
            Deselect list
        </Button>
    {/if}

    <DialogFooter>
        <Button onclick={handleSubmit}>{submitButtonTitle}</Button>
    </DialogFooter>
</Dialog>

{#snippet taskListItem(taskList)}
    <div class={taskList.parentTaskListId ? 'mt-0.5 ml-5' : ''}>
        <button
            type="button"
            onclick={() => handleSelectTaskList(taskList.id)}
            disabled={disabledTaskListId === taskList.id}
            class={join(
                'flex w-full cursor-default items-center gap-x-2 rounded-md px-2 py-1 hover:bg-neutral-100',
                'disabled:opacity-50 disabled:hover:bg-inherit',
                'dark:text-white dark:hover:bg-neutral-700',
                selectedTaskListId === taskList.id ? 'bg-neutral-100 dark:bg-neutral-700' : '',
            )}
        >
            <span class="font-medium">{taskList.title}</span>
        </button>

        {#each taskLists.filter((singleTaskList) => singleTaskList.parentTaskListId === taskList.id) as childTaskList (childTaskList.id)}
            {@render taskListItem(childTaskList)}
        {/each}
    </div>
{/snippet}
