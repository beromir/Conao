<script module>
    import App from '@/layouts/App.svelte';

    export const layout = (h, page) => {
        return h(App, { title: 'Closed Tasks' }, [page]);
    };
</script>

<script lang="ts">
    import TaskListItem from '@/components/TaskListItem.svelte';
    import Header from '@/components/solior/Header.svelte';
    import Main from '@/layouts/partials/Main.svelte';

    let { groupedTasks }: { groupedTasks: object[] } = $props();
</script>

<Header heading="Closed Tasks" />

<Main>
    <div class="space-y-6">
        {#each Object.keys(groupedTasks) as key (key)}
            <h3>{key}</h3>

            <ul class="mt-2">
                {#each groupedTasks[key] as task (task.id)}
                    <TaskListItem {task} markClosed={false} />
                {/each}
            </ul>
        {/each}
    </div>
</Main>
