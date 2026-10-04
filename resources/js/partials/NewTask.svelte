<script lang="ts">
    import type { TaskList } from '@/types';
    import { useForm } from '@inertiajs/svelte';
    import { refreshTaskLists } from '@/utils';
    import Field from '@/components/solior/Field.svelte';
    import Label from '@/components/solior/Label.svelte';
    import Input from '@/components/solior/Input.svelte';
    import Button from '@/components/solior/Button.svelte';
    import Plus from '@/icons/16/solid/Plus.svelte';
    import type { SvelteHTMLElements } from 'svelte/elements';
    import { Model } from '@/enums';

    let { taskList, ...props }: { taskList?: TaskList } & SvelteHTMLElements['div'] = $props();

    const form = useForm({
        title: '',
        parentListId: taskList?.id ?? '',
        parentListType: taskList ? Model.TaskList : '',
    });

    function store(e: any) {
        e.preventDefault();
        form.post(route('tasks.store'), {
            onSuccess: () => {
                refreshTaskLists();
                reset();
            },
        });
    }

    function reset() {
        form.resetAndClearErrors();
    }
</script>

<div {...props}>
    <form onsubmit={store} class="flex items-center gap-x-4">
        <Field id="task-title" class="max-w-xs flex-1">
            <Label class="sr-only">Task title</Label>
            <Input
                type="text"
                bind:value={form.title}
                required
                autoComplete="off"
                placeholder="New task"
                invalid={!!form.errors.title}
                class="mt-0!"
            />
            {form.errors.title && form.errors.title}
        </Field>

        <Button type="submit">
            <Plus />
            Add
        </Button>
    </form>
</div>
