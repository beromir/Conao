<script lang="ts">
    import type { Snippet } from 'svelte';
    import type { HTMLDialogAttributes } from 'svelte/elements';
    import { join } from '@/utils';

    let {
        cancel,
        children,
        ...props
    }: {
        cancel?: () => void;
        children: Snippet;
    } & HTMLDialogAttributes = $props();

    let drawer: HTMLDialogElement;

    export function show() {
        drawer.showModal();
    }

    export function hide() {
        drawer.close();
    }

    function handleClick(e: Event) {
        const target = e.target as HTMLElement;

        if (target === drawer || target.tagName === 'A') {
            cancel ? cancel() : hide();
        }
    }

    function handleCancel(event: Event) {
        event.preventDefault();

        cancel ? cancel() : hide();
    }
</script>

<dialog
    bind:this={drawer}
    onclick={handleClick}
    oncancel={handleCancel}
    {...props}
    class={join('size-full min-h-screen min-w-screen bg-transparent pr-12 backdrop:bg-neutral-200/50 dark:backdrop:bg-neutral-950/50', props.class)}
>
    <div class="h-full border-r border-neutral-300 bg-neutral-100 p-2 shadow-sm dark:border-neutral-800 dark:bg-neutral-950 dark:shadow-none">
        {@render children?.()}
    </div>
</dialog>
