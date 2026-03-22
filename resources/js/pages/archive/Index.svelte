<script module>
    import App from '@/layouts/App.svelte';

    export const layout = (h, page) => {
        return h(App, { title: 'Archive' }, [page]);
    };
</script>

<script lang="ts">
    import type { TaskList } from '@/types';
    import Header from '@/components/solior/Header.svelte';
    import Main from '@/layouts/partials/Main.svelte';
    import { Link } from '@inertiajs/svelte';

    let { archivedTaskLists }: { archivedTaskLists: TaskList[] } = $props();
</script>

<Header heading="Archive" />

<Main>
    <ul>
        {#each archivedTaskLists as archivedTaskList (archivedTaskList.id)}
            <li class="dark:text-white">
                <Link href={route('taskLists.show', archivedTaskList.id)}>
                    {archivedTaskList.title}
                </Link>
            </li>
        {/each}
    </ul>
</Main>
