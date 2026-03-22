<script lang="ts">
    import type { Snippet } from 'svelte';
    import type { SvelteHTMLElements } from 'svelte/elements';
    import { join } from '@/utils';

    let { children, ...props }: { children: Snippet } & SvelteHTMLElements['div'] = $props();
</script>

<div {...props} class={join('mt-10 flex items-center justify-end gap-x-3', props.class)}>
    {@render children()}
</div>
