<script lang="ts">
    import type { Snippet } from 'svelte';
    import Popover from '@/components/solior/Popover.svelte';

    let {
        id,
        position,
        fullWidth,
        closeOnClick,
        children,
    }: {
        id: string;
        position?: string;
        fullWidth?: boolean;
        closeOnClick?: boolean;
        children: Snippet;
    } = $props();
</script>

<Popover
    {id}
    {position}
    {fullWidth}
    {closeOnClick}
    gap={2}
    class="rounded-xl bg-white p-1 shadow-xs inset-ring inset-ring-neutral-200 dark:bg-neutral-800 dark:shadow-none dark:inset-ring-neutral-700"
>
    <div class="grid">
        {@render children?.()}
    </div>
</Popover>
