<script lang="ts">
    import { page, router } from '@inertiajs/svelte';
    import { dispatchCustomEvent } from '@/utils';
    import Inbox from '@/icons/20/solid/Inbox.svelte';
    import CalendarDays from '@/icons/20/solid/CalendarDays.svelte';
    import Star from '@/icons/20/solid/Star.svelte';
    import MenuItem from '@/components/MenuItem.svelte';
    import SidebarItem from '@/components/solior/SidebarItem.svelte';
    import CheckCircle from '@/icons/20/solid/CheckCircle.svelte';
    import ArchiveBox from '@/icons/20/solid/ArchiveBox.svelte';
    import Sidebar from '@/components/solior/Sidebar.svelte';
    import Plus from '@/icons/16/solid/Plus.svelte';
    import SidebarSection from '@/components/solior/SidebarSection.svelte';
    import type { TaskList, Theme } from '@/types';
    import Button from '@/components/solior/Button.svelte';
    import Dropdown from '@/components/solior/Dropdown.svelte';
    import DropdownItem from '@/components/solior/DropdownItem.svelte';
    import AdjustmentsHorizontal from '@/icons/20/solid/AdjustmentsHorizontal.svelte';
    import Sun from '@/icons/16/solid/Sun.svelte';
    import Moon from '@/icons/16/solid/Moon.svelte';
    import ComputerDesktop from '@/icons/16/solid/ComputerDesktop.svelte';
    import Check from '@/icons/16/solid/Check.svelte';
    import { getTheme, applyTheme } from '@/utils/localSettings';
    import DropdownDivider from '@/components/solior/DropdownDivider.svelte';
    import ArrowRightStartOnRectangle from '@/icons/16/solid/ArrowRightStartOnRectangle.svelte';
    import SidebarFooter from '@/components/solior/SidebarFooter.svelte';
    import SidebarBody from '@/components/solior/SidebarBody.svelte';

    let taskLists: TaskList[] = $derived($page.props.taskLists);

    let theme: Theme = $state(getTheme());

    const uniqueId = $props.id();

    function setTheme(value: Theme) {
        theme = value;
        applyTheme(value);
    }

    function handleLogout() {
        router.flushAll();

        router.post(route('logout'));
    }
</script>

<Sidebar>
    <SidebarBody>
        <SidebarSection>
            <SidebarItem href={route('inbox')} current={route('inbox').includes($page.url)}>
                <Inbox /> Inbox
            </SidebarItem>
            <SidebarItem href={route('today')} current={route('today').includes($page.url)}>
                <Star /> Today
            </SidebarItem>
            <SidebarItem href={route('upcoming')} current={route('upcoming').includes($page.url)}>
                <CalendarDays /> Upcoming
            </SidebarItem>

            <SidebarItem href={route('closedTasks')} current={route('closedTasks').includes($page.url)}>
                <CheckCircle /> Closed Tasks
            </SidebarItem>
            <SidebarItem href={route('archive')} current={route('archive').includes($page.url)}>
                <ArchiveBox /> Archive
            </SidebarItem>
        </SidebarSection>

        {#if taskLists.length}
            <SidebarSection>
                {#each taskLists.filter((taskList) => !taskList.parentTaskListId) as taskList (taskList.id)}
                    <MenuItem {taskList} {taskLists} />
                {/each}
            </SidebarSection>
        {/if}
    </SidebarBody>

    <SidebarFooter>
        <SidebarSection class="flex items-center justify-between gap-x-2">
            <SidebarItem onclick={() => dispatchCustomEvent('taskLists.create')} class="flex-1">
                <Plus /> New List
            </SidebarItem>

            <Button plain popovertarget={`${uniqueId}-settings`} aria-label="Settings">
                <AdjustmentsHorizontal class="size-4.5!" />
            </Button>

            <Dropdown id={`${uniqueId}-settings`} position="top-end">
                <DropdownItem onclick={() => setTheme('light')}>
                    <Sun /> Light {#if theme === 'light'}<Check class="ml-auto" />{/if}
                </DropdownItem>
                <DropdownItem onclick={() => setTheme('dark')}>
                    <Moon /> Dark {#if theme === 'dark'}<Check class="ml-auto" />{/if}
                </DropdownItem>
                <DropdownItem onclick={() => setTheme('system')}>
                    <ComputerDesktop /> System {#if theme === 'system'}<Check class="ml-auto" />{/if}
                </DropdownItem>

                <DropdownDivider />

                <DropdownItem onclick={handleLogout} color="error"><ArrowRightStartOnRectangle /> Logout</DropdownItem>
            </Dropdown>
        </SidebarSection>
    </SidebarFooter>
</Sidebar>
