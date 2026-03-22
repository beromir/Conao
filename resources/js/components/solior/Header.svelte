<script lang="ts">
    import type { Snippet } from 'svelte';

    let { heading, children }: { heading: string; children?: Snippet } = $props();
</script>

<div class="container flex items-center justify-between border-b border-neutral-200 pb-2 dark:border-neutral-700">
    <h1 class="text-2xl font-medium">{heading}</h1>

    {@render children?.()}
</div>
