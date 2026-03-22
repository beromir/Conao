<script lang="ts">
    import { type Snippet } from 'svelte';
    import { page } from '@inertiajs/svelte';
    import TaskDialog from '@/partials/TaskDialog.svelte';
    import { dispatchCustomEvent } from '@/utils';
    import TaskListDialog from '@/partials/TaskListDialog.svelte';
    import TaskListSelectDialog from '@/partials/TaskListSelectDialog.svelte';
    import TaskListGroupDialog from '@/partials/TaskListGroupDialog.svelte';
    import DropTarget from '@/components/solior/DropTarget.svelte';
    import { checkDropItemType } from '@/events/moveEvent';
    import { Model } from '@/enums';
    import Bars3 from '@/icons/24/solid/Bars3.svelte';
    import Menu from '@/layouts/partials/Menu.svelte';
    import Drawer from '@/components/solior/Drawer.svelte';

    let { title, children }: { title: string; children: Snippet } = $props();

    let mobileMenu: Drawer;

    function handleMobileMenuButtonClick(): void {
        mobileMenu.open ? mobileMenu.hide() : mobileMenu.show();
    }
</script>

<svelte:head>
    <title>{title} | {$page.props.appName}</title>
</svelte:head>

<div class="flex min-h-screen">
    <div
        class="hidden w-2xs border-r border-neutral-300 bg-neutral-100 shadow-sm md:block dark:border-neutral-800 dark:bg-neutral-950 dark:shadow-none"
    >
        <Menu />
    </div>

    <DropTarget
        accept={(event) => checkDropItemType(event, Model.Task)}
        drop={(event) => dispatchCustomEvent('tasks.move', event)}
        role="main"
        class="grow py-6 md:py-16"
    >
        {@render children?.()}
    </DropTarget>
</div>

<button
    type="button"
    onclick={handleMobileMenuButtonClick}
    aria-label="Open mobile menu"
    class="fixed bottom-12 left-1/2 flex w-54 -translate-x-1/2 justify-center rounded-full bg-white/80 px-4 py-2 ring ring-neutral-200 backdrop-blur-lg md:hidden dark:bg-neutral-950/80 dark:ring-neutral-700/60"
>
    <Bars3 class="size-7" />
</button>

<Drawer bind:this={mobileMenu} class="md:hidden"><Menu /></Drawer>

<TaskDialog />
<TaskListDialog />
<TaskListGroupDialog />
<TaskListSelectDialog />
