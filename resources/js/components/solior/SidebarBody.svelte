<script lang="ts">
    import type { Snippet } from 'svelte';
    import type { SvelteHTMLElements } from 'svelte/elements';

    let { children, ...props }: { children: Snippet } & SvelteHTMLElements['div'] = $props();
</script>

<div {...props} class="flex-1 overflow-y-auto">
    {@render children()}
</div>
