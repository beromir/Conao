<script module>
    import App from '@/layouts/App.svelte';

    export const layout = (h, page) => {
        return h(App, { title: 'Inbox' }, [page]);
    };
</script>

<script lang="ts">
    import TaskList from '@/components/TaskList.svelte';
    import type { Task } from '@/types';
    import Header from '@/components/solior/Header.svelte';
    import Main from '@/layouts/partials/Main.svelte';

    let { tasks }: { tasks: Task[] } = $props();
</script>

<Header heading="Inbox" />

<Main>
    <TaskList {tasks} showCompletedTasks={true} />
</Main>
