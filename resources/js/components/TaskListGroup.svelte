<script lang="ts">
    import type { TaskListGroup } from '@/types';
    import TaskList from '@/components/TaskList.svelte';
    import Button from '@/components/solior/Button.svelte';
    import EllipsisHorizontal from '@/icons/16/solid/EllipsisHorizontal.svelte';
    import Dropdown from '@/components/solior/Dropdown.svelte';
    import DropdownItem from '@/components/solior/DropdownItem.svelte';
    import PencilSquare from '@/icons/16/solid/PencilSquare.svelte';
    import Trash from '@/icons/16/solid/Trash.svelte';
    import { dispatchCustomEvent, refreshTaskLists } from '@/utils';
    import { router } from '@inertiajs/svelte';
    import DropTarget from '@/components/solior/DropTarget.svelte';
    import { checkDropItemType, handleMoveEvent } from '@/events/moveEvent';
    import { Model } from '@/enums';
    import Plus from '@/icons/16/solid/Plus.svelte';

    let { taskListGroup }: { taskListGroup: TaskListGroup } = $props();

    const uniqueId = $props.id();

    function handleDelete() {
        router.delete(route('taskListGroups.destroy', taskListGroup.id), {
            onSuccess: () => refreshTaskLists(),
        });
    }
</script>

<DropTarget
    accept={(event) => checkDropItemType(event, Model.Task)}
    drop={(event) => handleMoveEvent(event, taskListGroup)}
    role="group"
    aria-label={taskListGroup.title}
    class="rounded-lg bg-neutral-100/40 p-5 shadow-xs ring ring-neutral-300/40 dark:bg-neutral-800/40 dark:shadow-none dark:ring-neutral-800"
>
    <div class="flex items-center justify-between border-b border-neutral-200 py-1.5 dark:border-white/10">
        <h2 class="text-lg font-medium">{taskListGroup.title}</h2>

        <div class="flex items-center gap-x-1">
            <Button plain onclick={() => dispatchCustomEvent('tasks.create', { parentListId: taskListGroup.id, parentListType: taskListGroup.type })}>
                <Plus />
            </Button>
            <Button plain popovertarget={`${uniqueId}-task-list-group-options`}>
                <EllipsisHorizontal />
            </Button>
        </div>

        <Dropdown id={`${uniqueId}-task-list-group-options`} position="bottom-end">
            <DropdownItem onclick={() => dispatchCustomEvent('taskListGroups.edit', taskListGroup)}>
                <PencilSquare />
                Edit
            </DropdownItem>
            <DropdownItem onclick={handleDelete} color="error">
                <Trash />
                Delete
            </DropdownItem>
        </Dropdown>
    </div>

    <div class="mt-6">
        <TaskList tasks={taskListGroup.tasks ?? []} showNewTaskForm={false} showEmptyState={false} />
    </div>
</DropTarget>
