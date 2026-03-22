<script lang="ts">
    import type { Snippet } from 'svelte';
    import type { SvelteHTMLElements } from 'svelte/elements';

    let { children, ...props }: { children: Snippet } & SvelteHTMLElements['div'] = $props();
</script>

<hr class="mx-2 border-neutral-300/70 dark:border-neutral-800/90" />
<div {...props}>
    {@render children()}
</div>
