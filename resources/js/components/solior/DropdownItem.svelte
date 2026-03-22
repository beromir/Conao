<script lang="ts">
    import type { Snippet } from 'svelte';
    import { Link } from '@inertiajs/svelte';
    import { join } from '@/utils';

    let { onclick, href, color = 'primary', children }: { onclick: any; href: string; color: 'primary' | 'error'; children: Snippet } = $props();

    function handleClick(e: Event) {
        const target = e.target as HTMLElement;

        target?.closest('[popover]')?.hidePopover();
    }

    const className = join(
        'flex items-center gap-x-2 rounded-lg px-3 py-2 text-left text-sm font-medium text-neutral-800',
        'icon:size-4 icon:text-neutral-500 hover:icon:text-white!',
        'dark:text-white dark:icon:text-neutral-300',
        color === 'primary' ? 'hover:bg-primary-600 hover:text-white' : '',
        color === 'error' ? 'hover:bg-red-700 hover:text-white' : '',
    );
</script>

{#if href}
    <Link {href} onclick={handleClick} class={className}>{@render children?.()}</Link>
{:else}
    <button type="button" {onclick} class={className}>
        {@render children?.()}
    </button>
{/if}
