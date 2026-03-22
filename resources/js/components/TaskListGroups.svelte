<script lang="ts">
    import type { TaskListGroup as TaskListGroupType } from '@/types';
    import TaskListGroup from '@/components/TaskListGroup.svelte';
    import type { SvelteHTMLElements } from 'svelte/elements';
    import { join } from '@/utils';

    let { taskListGroups, ...props }: { taskListGroups: TaskListGroupType[] } & SvelteHTMLElements['div'] = $props();
</script>

<div {...props} class={join('grid gap-6 md:grid-cols-2', props.class)}>
    {#each taskListGroups as taskListGroup (taskListGroup.id)}
        <TaskListGroup {taskListGroup} />
    {/each}
</div>
