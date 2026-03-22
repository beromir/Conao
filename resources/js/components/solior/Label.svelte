<script lang="ts">
    import type { Snippet } from 'svelte';
    import { join } from '@/utils';
    import type { HTMLLabelAttributes } from 'svelte/elements';

    let { children, ...props }: { children: Snippet } & HTMLLabelAttributes = $props();
</script>

<label class={join('block text-sm/6 font-medium text-neutral-700 dark:text-neutral-300', props.class)}>
    {@render children?.()}
</label>
