<script lang="ts">
    import type { SvelteHTMLElements } from 'svelte/elements';
    import { join } from '@/utils';
    import type { Snippet } from 'svelte';

    let {
        accept,
        drop,
        dragOver,
        dragLeave,
        as = 'div',
        children,
        ...props
    }: {
        accept: (event: DragEvent) => boolean;
        drop: (event: DragEvent) => void;
        dragOver?: (event: DragEvent) => void;
        dragLeave?: (event: DragEvent) => void;
        as?: keyof HTMLElementTagNameMap;
        children: Snippet;
    } & SvelteHTMLElements['div'] = $props();

    let isDragOver: boolean = $state(false);

    function handleDragOver(event: DragEvent) {
        if (!handleEvent(event)) return;

        isDragOver = true;

        dragOver?.(event);
    }

    function handleDragLeave(event: DragEvent) {
        if (!handleEvent(event)) return;

        isDragOver = false;

        dragLeave?.(event);
    }

    function handleDrop(event: DragEvent) {
        if (!handleEvent(event)) return;

        isDragOver = false;

        drop(event);
    }

    function handleEvent(event: DragEvent): boolean {
        event.preventDefault();
        event.stopPropagation();

        return accept(event);
    }
</script>

<svelte:element
    this={as}
    ondragover={handleDragOver}
    ondragleave={handleDragLeave}
    ondrop={handleDrop}
    {...props}
    class={join(isDragOver ? 'inset-ring-2 inset-ring-primary-500/80' : '', props.class)}
>
    {@render children?.()}
</svelte:element>
