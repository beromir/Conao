<script lang="ts">
    import type { Snippet } from 'svelte';
    import type { SvelteHTMLElements } from 'svelte/elements';
    import { join } from '@/utils';

    let { children, ...props }: { children: Snippet } & SvelteHTMLElements['div'] = $props();
</script>

<div {...props} class={join('space-y-1 p-4', props.class)}>
    {@render children()}
</div>
