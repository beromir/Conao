<script lang="ts">
    import { onMount } from 'svelte';
    import type { Snippet } from 'svelte';

    let { id, children }: { id: string; children: Snippet } = $props();
    let field: HTMLDivElement;

    onMount(() => {
        field.querySelector('label').setAttribute('for', id);
        field.querySelector('input').setAttribute('id', id);
    });
</script>

<div bind:this={field} class="flex items-center gap-x-3 [&>label]:font-normal!">
    {@render children?.()}
</div>
