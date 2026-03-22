<script module>
    import App from '@/layouts/App.svelte';

    export const layout = (h, page) => {
        return h(App, { title: page.props.taskList.title }, [page]);
    };
</script>

<script lang="ts">
    import TaskList from '@/components/TaskList.svelte';
    import type { Task, TaskList as TaskListType, TaskListGroup } from '@/types';
    import EllipsisHorizontal from '@/icons/16/solid/EllipsisHorizontal.svelte';
    import Button from '@/components/solior/Button.svelte';
    import Dropdown from '@/components/solior/Dropdown.svelte';
    import Header from '@/components/solior/Header.svelte';
    import DropdownItem from '@/components/solior/DropdownItem.svelte';
    import PencilSquare from '@/icons/16/solid/PencilSquare.svelte';
    import Trash from '@/icons/16/solid/Trash.svelte';
    import ArchiveBoxArrowDown from '@/icons/16/solid/ArchiveBoxArrowDown.svelte';
    import ArrowDownOnSquare from '@/icons/16/solid/ArrowDownOnSquare.svelte';
    import SquaresPlus from '@/icons/16/solid/SquaresPlus.svelte';
    import DropdownDivider from '@/components/solior/DropdownDivider.svelte';
    import Main from '@/layouts/partials/Main.svelte';
    import { router } from '@inertiajs/svelte';
    import { dispatchCustomEvent, refreshTaskLists } from '@/utils';
    import ArchiveBoxXMark from '@/icons/16/solid/ArchiveBoxXMark.svelte';
    import TaskListGroups from '@/components/TaskListGroups.svelte';
    import { handleMoveEvent } from '@/events/moveEvent';

    let { taskList, tasks, taskListGroups }: { taskList: TaskListType; tasks: Task[]; taskListGroups: TaskListGroup[] } = $props();

    function handleMergeInto() {
        dispatchCustomEvent('taskLists.select', {
            disabled: taskList.id,
            options: {
                modalTitle: 'Merge into',
                submitButtonTitle: 'Merge',
                showDeselectButton: false,
            },
        });

        window.addEventListener(
            'taskLists.selected',
            (e) => {
                router.post(
                    route('taskLists.merge'),
                    {
                        sourceTaskListId: taskList.id,
                        targetTaskListId: e.detail ?? '',
                    },
                    {
                        onSuccess: () => {
                            // Flush prefetch cache to render the correct state
                            router.flushAll();
                            refreshTaskLists();
                        },
                    },
                );
            },
            { once: true },
        );
    }

    function handleArchive() {
        router.post(
            '/archive',
            {
                taskListId: taskList.id,
            },
            {
                onSuccess: () => refreshTaskLists(),
            },
        );
    }

    function handleUnarchive() {
        router.post(
            '/unarchive',
            { taskListId: taskList.id },
            {
                onSuccess: () => refreshTaskLists(),
            },
        );
    }

    function handleDelete() {
        router.delete(route('taskLists.destroy', taskList.id), {
            onSuccess: () => refreshTaskLists(),
        });
    }
</script>

<svelte:window ontasks.move={(event) => handleMoveEvent(event.detail, taskList)} />

<Header heading={taskList.title}>
    <Button plain popovertarget="header-actions">
        <EllipsisHorizontal />
    </Button>

    <Dropdown id="header-actions" position="bottom-end">
        {#if !taskList.isArchived}
            <DropdownItem onclick={() => dispatchCustomEvent('taskLists.edit', taskList)}>
                <PencilSquare />
                Edit
            </DropdownItem>
            <DropdownItem onclick={() => dispatchCustomEvent('taskListGroups.create', taskList.id)}>
                <SquaresPlus />
                Add Group
            </DropdownItem>
            <DropdownDivider />
            <DropdownItem onclick={handleMergeInto}>
                <ArrowDownOnSquare />
                Merge into
            </DropdownItem>
            <DropdownItem onclick={handleArchive}>
                <ArchiveBoxArrowDown />
                Archive
            </DropdownItem>
        {:else}
            <DropdownItem onclick={handleUnarchive}>
                <ArchiveBoxXMark />
                Unarchive
            </DropdownItem>
        {/if}

        <DropdownDivider />
        <DropdownItem onclick={handleDelete} color="error">
            <Trash />
            Delete
        </DropdownItem>
    </Dropdown>
</Header>

<Main>
    <TaskList {tasks} {taskList} showNewTaskForm={!taskList.isArchived} showEmptyState={!taskListGroups} />

    <TaskListGroups {taskListGroups} class="mt-16" />
</Main>
