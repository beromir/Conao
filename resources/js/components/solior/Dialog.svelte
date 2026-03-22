<script lang="ts">
    import type { Snippet } from 'svelte';

    let {
        title,
        cancel,
        children,
    }: {
        title?: string;
        cancel?: () => void;
        children: Snippet;
    } = $props();

    let dialog: HTMLDialogElement;

    export function show() {
        dialog.showModal();
    }

    export function hide() {
        dialog.close();
    }

    function handleCLick(event: Event) {
        if (event.target === dialog) {
            cancel ? cancel() : hide();
        }
    }

    function handleCancel(event: Event) {
        event.preventDefault();

        cancel ? cancel() : hide();
    }
</script>

<dialog
    bind:this={dialog}
    onclick={handleCLick}
    oncancel={handleCancel}
    class="m-auto rounded-xl bg-transparent shadow-xl ring ring-neutral-200 backdrop:bg-neutral-200/50 dark:ring-neutral-800 dark:backdrop:bg-neutral-950/50"
>
    <div class="w-screen max-w-full bg-neutral-50 p-4 text-black sm:w-md sm:p-8 dark:bg-black dark:text-white">
        {#if title}
            <h2 class="mb-6 text-xl font-semibold">{title}</h2>
        {/if}

        {@render children?.()}
    </div>
</dialog>
