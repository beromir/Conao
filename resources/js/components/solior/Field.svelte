<script lang="ts">
    import { onMount } from 'svelte';
    import type { Snippet } from 'svelte';
    import { join } from '@/utils';
    import type { SvelteHTMLElements } from 'svelte/elements';

    let { id, children, ...props }: { id: string; children: Snippet } & SvelteHTMLElements['div'] = $props();
    let field: HTMLDivElement;

    const uniqueId = $props.id();
    const fieldId = uniqueId + '-' + id;

    onMount(() => {
        field.querySelector('label')?.setAttribute('for', fieldId);
        field.querySelector('input')?.setAttribute('id', fieldId);
        field.querySelector('textarea')?.setAttribute('id', fieldId);
    });
</script>

<div bind:this={field} class={join('group *:[label]:pb-2', props.class)}>
    {@render children?.()}
</div>
