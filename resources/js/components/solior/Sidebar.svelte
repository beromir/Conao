<script lang="ts">
    import type { Snippet } from 'svelte';
    import type { SvelteHTMLElements } from 'svelte/elements';

    let { children, ...props }: { children: Snippet } & SvelteHTMLElements['div'] = $props();
</script>

<div {...props} class="sticky top-0 flex h-full max-h-screen flex-col">
    {@render children()}
</div>
