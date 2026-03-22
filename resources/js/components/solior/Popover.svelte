<script lang="ts">
    import type { Snippet } from 'svelte';

    let {
        id,
        position = 'bottom',
        gap = 0,
        fullWidth = false,
        closeOnClick = true,
        children,
        ...props
    }: {
        id: string;
        position: string;
        gap: number;
        fullWidth: boolean;
        closeOnClick: boolean;
        children: Snippet;
    } = $props();

    let popover: HTMLDivElement;

    function getGap(): string {
        switch (position) {
            case 'top-start':
            case 'top':
            case 'top-end':
                return `margin-bottom: calc(var(--spacing) * ${gap})`;
            case 'right-start':
            case 'right':
            case 'right-end':
                return `margin-left: calc(var(--spacing) * ${gap})`;
            case 'bottom-start':
            case 'bottom':
            case 'bottom-end':
                return `margin-top: calc(var(--spacing) * ${gap})`;
            case 'left-start':
            case 'left':
            case 'left-end':
                return `margin-right: calc(var(--spacing) * ${gap})`;
        }

        return '';
    }

    function handleClick() {
        if (!closeOnClick) return;

        setTimeout(() => {
            popover.hidePopover();
        }, 100);
    }
</script>

<div
    bind:this={popover}
    onclick={handleClick}
    {id}
    {...props}
    popover="auto"
    class={['fixed', position, fullWidth ? 'anchor-width' : '', props.class]}
    style={getGap()}
>
    {@render children?.()}
</div>
