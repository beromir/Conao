<script lang="ts">
    import type { Snippet } from 'svelte';
    import type { HTMLButtonAttributes } from 'svelte/elements';
    import { join } from '@/utils';
    import { Link } from '@inertiajs/svelte';

    let {
        href,
        current = false,
        type = 'button',
        children,
        ...props
    }: {
        href?: string;
        current?: boolean;
        type?: HTMLButtonAttributes['type'];
        children: Snippet;
    } = $props();

    const className = $derived(
        join(
            'flex items-center gap-x-3 rounded-lg p-2 text-sm font-medium text-neutral-700 icon:text-neutral-600 dark:text-neutral-200 dark:icon:text-neutral-300',
            'hover:bg-neutral-200/80 hover:text-neutral-950 hover:icon:text-neutral-800 dark:hover:bg-neutral-700/40 dark:hover:text-white dark:hover:icon:text-white',
            current ? 'bg-neutral-200/80 text-neutral-950 icon:text-neutral-800 dark:bg-neutral-700/40 dark:text-white dark:icon:text-white' : '',
            props.class,
        ),
    );
</script>

{#if href}
    <Link {href} prefetch {...props} class={className}>
        {@render children()}
    </Link>
{:else}
    <button {type} {...props} class={className}>
        {@render children()}
    </button>
{/if}
