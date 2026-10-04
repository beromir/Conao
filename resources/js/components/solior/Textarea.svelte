<script lang="ts">
    import type { HTMLTextareaAttributes } from 'svelte/elements';
    import { join } from '@/utils';

    let {
        value = $bindable(''),
        rows = 2,
        ...props
    }: {
        value: string;
        rows: HTMLTextareaAttributes['rows'];
    } & HTMLTextareaAttributes = $props();
</script>

<textarea
    bind:value
    {rows}
    {...props}
    class={join(
        'block min-h-12 w-full rounded-md bg-neutral-100/70 px-3 py-1.5 text-base ring ring-neutral-300',
        'group-hover:ring-neutral-400/70 hover:ring-neutral-400/70 focus:outline-2 focus:outline-primary-600',
        'sm:text-sm/6',
        'dark:bg-neutral-800/70 dark:ring-neutral-700',
        'dark:group-hover:ring-neutral-600 dark:hover:ring-neutral-600',
        props.class,
    )}></textarea>
