<script lang="ts">
    import type { HTMLInputAttributes, HTMLInputTypeAttribute } from 'svelte/elements';
    import { join } from '@/utils';

    let {
        type = 'text',
        value = $bindable(''),
        ...props
    }: {
        type: HTMLInputTypeAttribute;
        value: string;
    } & HTMLInputAttributes = $props();
</script>

<input
    {type}
    bind:value
    {...props}
    class={join(
        'block w-full rounded-md bg-neutral-100/70 px-3 py-1.5 text-base ring ring-neutral-300',
        'group-hover:ring-neutral-400/70 hover:ring-neutral-400/70 focus:outline-2 focus:outline-primary-600',
        'sm:text-sm/6',
        'dark:bg-neutral-800/70 dark:ring-neutral-700',
        'dark:group-hover:ring-neutral-600 dark:hover:ring-neutral-600',
        props.class,
    )}
/>
