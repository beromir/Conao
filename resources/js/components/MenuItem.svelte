<script lang="ts">
    import type { TaskList } from '@/types';
    import SidebarItem from '@/components/solior/SidebarItem.svelte';
    import MenuItem from './MenuItem.svelte';
    import DropTarget from '@/components/solior/DropTarget.svelte';
    import { checkDropItemType, handleMoveEvent } from '@/events/moveEvent';
    import { Model } from '@/enums';
    import { page } from '@inertiajs/svelte';

    let { taskList, taskLists }: { taskList: TaskList; taskLists: TaskList[] } = $props();
</script>

<div class={taskList.parentTaskListId ? 'mt-0.5 ml-5' : ''}>
    <DropTarget accept={(event) => checkDropItemType(event, Model.Task)} drop={(event) => handleMoveEvent(event, taskList)}>
        <SidebarItem href={route('taskLists.show', taskList.id)} current={route('taskLists.show', taskList.id).includes($page.url)}>
            {taskList.title}

            {#if taskList.tasksCount}
                <div class="ml-auto text-xs font-semibold text-neutral-600 dark:text-neutral-300">
                    {taskList.tasksCount}
                </div>
            {/if}
        </SidebarItem>
    </DropTarget>

    {#each taskLists.filter((singleTaskList) => singleTaskList.parentTaskListId === taskList.id) as childTaskList (childTaskList.id)}
        <MenuItem taskList={childTaskList} {taskLists} />
    {/each}
</div>
