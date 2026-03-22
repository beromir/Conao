<script lang="ts">
    import { router } from '@inertiajs/svelte';
    import type { Task } from '@/types';
    import { dispatchCustomEvent, join, refreshTaskLists } from '@/utils';
    import Checkbox from '@/components/solior/Checkbox.svelte';
    import Flag from '@/icons/16/solid/Flag.svelte';
    import Star from '@/icons/16/solid/Star.svelte';
    import ListBullet from '@/icons/16/solid/ListBullet.svelte';
    import DocumentText from '@/icons/16/solid/DocumentText.svelte';
    import Dropdown from '@/components/solior/Dropdown.svelte';
    import EllipsisHorizontal from '@/icons/16/solid/EllipsisHorizontal.svelte';
    import Button from '@/components/solior/Button.svelte';
    import DropdownItem from '@/components/solior/DropdownItem.svelte';
    import Trash from '@/icons/16/solid/Trash.svelte';
    import { setDragEventData } from '@/events/moveEvent';

    let { task, markClosed = true }: { task: Task; markClosed?: boolean } = $props();

    const isOpen = task.state === 'open';

    const today = new Date();
    let datePlusTwo = new Date();
    datePlusTwo.setDate(today.getDate() + 2);
    const plannedFor = $derived(new Date(task.plannedFor));
    const deadline = $derived(new Date(task.deadline));

    const uniqueId = $props.id();

    function changeState(task: Task) {
        const state = task.state;

        router.patch(
            route('tasks.changeState'),
            {
                id: task.id,
                state: state === 'open' ? 'closed' : 'open',
            },
            {
                onSuccess: () => refreshTaskLists(),
            },
        );
    }

    function deleteTask(task: Task) {
        router.delete(route('tasks.destroy', task.id), {
            onSuccess: () => refreshTaskLists(),
        });
    }
</script>

{#if isOpen}
    <!-- svelte-ignore a11y_no_noninteractive_element_interactions, a11y_click_events_have_key_events -->
    <li
        onclick={() => dispatchCustomEvent('tasks.edit', task)}
        ondragstart={(event) => setDragEventData(event, { type: task.type, id: task.id })}
        draggable="true"
        class={join(
            '-mx-3 flex min-h-11 items-center rounded-lg px-3 select-none hover:bg-neutral-100 md:py-1 dark:hover:bg-neutral-800',
            task.parentListTitle ? 'py-1' : '',
        )}
    >
        <div>
            {#if task.parentListTitle}
                <div class="mb-0.5 text-xs font-semibold tracking-wide text-neutral-600 md:mb-1 dark:text-neutral-400">
                    {task.parentListTitle}
                </div>
            {/if}

            <div class="flex items-center">
                <Checkbox onchange={() => changeState(task)} onclick={(e) => e.stopPropagation()} class="isolate" />
                {#if task.plannedFor && today >= plannedFor}
                    <Star class="-mr-1 ml-2 size-4 text-amber-500 dark:text-amber-400" />
                {:else if task.plannedFor && today < plannedFor}
                    <div class="ml-2 rounded-sm bg-neutral-200 px-1.5 py-0.5 text-xs dark:bg-neutral-700 dark:text-neutral-50">
                        {task.plannedForLabel}
                    </div>
                {/if}

                <div class="ml-2 dark:text-neutral-50">
                    {task.title}
                </div>

                {#if task.checklist || task.notes}
                    <div class="ml-1.5 flex items-center gap-x-0.5">
                        {#if task.checklist}
                            <ListBullet class="size-3.5 text-neutral-600 dark:text-neutral-400" />
                        {/if}
                        {#if task.notes}
                            <DocumentText class="size-3.5 text-neutral-600 dark:text-neutral-400" />
                        {/if}
                    </div>
                {/if}
            </div>
        </div>

        <div class="ml-auto flex items-center">
            {#if task.deadline}
                <div
                    class={join(
                        'flex items-center gap-x-1.5 text-xs',
                        today >= deadline
                            ? 'text-red-600 dark:text-red-500'
                            : datePlusTwo >= deadline
                              ? 'text-orange-700 dark:text-amber-500'
                              : 'text-neutral-800 dark:text-neutral-300',
                    )}
                >
                    <Flag class="size-3.5" />
                    <p class="flex-none">
                        {task.deadlineLabel}
                    </p>
                </div>
            {/if}
        </div>
    </li>
{:else}
    <li
        class={join(
            '-mx-3 flex items-center rounded-lg px-3 select-none hover:bg-neutral-100 md:py-1 dark:hover:bg-neutral-800',
            task.parentListTitle ? 'py-1' : '',
        )}
    >
        <div>
            {#if task.parentListTitle}
                <div class="mb-0.5 text-xs font-semibold tracking-wide text-neutral-600 md:mb-1 dark:text-neutral-400">
                    {task.parentListTitle}
                </div>
            {/if}
            <div class="flex items-center">
                <Checkbox onchange={() => changeState(task)} checked={!isOpen} class={join(markClosed ? 'opacity-60' : '')} />
                <div class={join('ml-2 dark:text-neutral-50', markClosed ? 'opacity-60' : '')}>
                    {task.title}
                </div>
            </div>
        </div>

        <Button plain popovertarget={`${uniqueId}-options`} class="ml-auto">
            <EllipsisHorizontal />
        </Button>

        <Dropdown id={`${uniqueId}-options`}>
            <DropdownItem onclick={() => deleteTask(task)} color="error">
                <Trash /> Delete
            </DropdownItem>
        </Dropdown>
    </li>
{/if}
