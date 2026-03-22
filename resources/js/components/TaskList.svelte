<script lang="ts">
    import Button from '@/components/solior/Button.svelte';
    import Plus from '@/icons/16/solid/Plus.svelte';
    import Minus from '@/icons/16/solid/Minus.svelte';
    import type { Task, TaskList } from '@/types';
    import TaskListItem from '@/components/TaskListItem.svelte';
    import NewTask from '@/partials/NewTask.svelte';
    import XCircle from '@/icons/24/outline/XCircle.svelte';

    interface Props {
        tasks: Task[];
        showNewTaskForm?: boolean;
        showCompletedTasks?: boolean;
        showEmptyState?: boolean;
        taskList?: TaskList;
    }

    let { tasks, showNewTaskForm = true, showCompletedTasks = true, showEmptyState = true, taskList }: Props = $props();

    let showClosedTasks: boolean = $state(false);

    const openTasks = $derived(tasks.filter((task) => task.state === 'open'));
    const closedTasks = $derived(tasks.filter((task) => task.state === 'closed'));
</script>

{#if showNewTaskForm}
    <NewTask {taskList} class="mb-6" />
{/if}

<ul>
    {#each openTasks as task (task.id)}
        <TaskListItem {task} />
    {:else}
        {#if showEmptyState}
            <li class="flex flex-col items-center gap-y-1.5 py-6">
                <XCircle class="size-7.5 text-neutral-400" />
                <span class="text-sm font-medium text-neutral-600 dark:text-neutral-300">No tasks found</span>
            </li>
        {/if}
    {/each}
</ul>

{#if showCompletedTasks && closedTasks.length}
    <div class="mt-4 flex justify-center">
        <Button onclick={() => (showClosedTasks = !showClosedTasks)} plain>
            {#if showClosedTasks}
                <Minus />
                Hide closed tasks
            {:else}
                <Plus />
                Show closed tasks
            {/if}
        </Button>
    </div>

    {#if showClosedTasks}
        <ul class="mt-2">
            {#each closedTasks as task (task.id)}
                <TaskListItem {task} />
            {/each}
        </ul>
    {/if}
{/if}
