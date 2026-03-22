<script lang="ts">
    import type { Snippet } from 'svelte';
    import { page } from '@inertiajs/svelte';
    import Logo from '@/components/Logo.svelte';

    let {
        title,
        heading,
        footer,
        children,
    }: {
        title: string;
        heading: string;
        footer: Snippet;
        children: Snippet;
    } = $props();
</script>

<svelte:head>
    <title>{title} | {$page.props.appName}</title>
</svelte:head>

<div
    class="flex min-h-screen flex-col justify-center bg-neutral-200 bg-radial from-neutral-100 py-12 sm:px-6 lg:px-8 dark:bg-neutral-950 dark:from-black"
>
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
        {#if heading}
            <div class="mb-12 flex flex-col items-center gap-6">
                <Logo class="h-12 w-auto text-primary-500" />

                <h1 class="text-3xl font-semibold dark:text-white">{heading}</h1>
            </div>
        {/if}

        <div class="bg-neutral-50/80 px-6 py-12 shadow ring ring-neutral-300/50 sm:rounded-lg sm:px-12 dark:bg-black/80 dark:ring-neutral-800">
            {@render children?.()}
        </div>

        {#if footer}
            <div class="mt-10 text-center text-sm/6 text-neutral-700 dark:text-neutral-400">
                {@render footer()}
            </div>
        {/if}
    </div>
</div>
