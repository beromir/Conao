<script lang="ts">
    import type { Snippet } from 'svelte';
    import { join } from '@/utils';
    import type { HTMLButtonAttributes } from 'svelte/elements';

    let {
        type = 'button',
        color = 'neutral',
        plain = false,
        children,
        ...props
    }: {
        type?: HTMLButtonAttributes['type'];
        plain?: boolean;
        color?: 'neutral' | 'primary';
        children: Snippet;
    } & HTMLButtonAttributes = $props();

    const colorClass = join(
        color === 'neutral' && !plain
            ? 'bg-white ring ring-neutral-300 shadow-xs hover:bg-white/50 hover:shadow-2xs dark:bg-neutral-800 dark:ring-neutral-700 dark:shadow-none dark:hover:bg-neutral-700/50'
            : '',
        color === 'primary' && !plain ? 'text-white bg-primary-600 hover:bg-primary-500' : '',
        plain ? 'hover:bg-neutral-200/80 dark:hover:bg-neutral-700/40' : '',
    );
</script>

<button
    {type}
    {...props}
    class={join(
        'flex items-center justify-center gap-x-2.5 rounded-md px-3 py-1.5 text-sm/6 font-semibold',
        'icon:-mx-0.5 icon:my-1 icon:text-neutral-500 dark:icon:text-neutral-400',
        'hover:icon:text-neutral-700 dark:hover:icon:text-neutral-200',
        'focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600',
        colorClass,
        props.class,
    )}
>
    {@render children?.()}
</button>
