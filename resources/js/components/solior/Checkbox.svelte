<script lang="ts">
    import type { HTMLInputAttributes } from 'svelte/elements';

    let { checked = $bindable(false), ...props }: { checked: boolean } & HTMLInputAttributes = $props();
</script>

<div class="group grid size-4">
    <input
        type="checkbox"
        bind:checked
        {...props}
        class={[
            'col-start-1 row-start-1 appearance-none rounded-sm bg-neutral-100/70 ring ring-neutral-300 forced-colors:appearance-auto',
            'checked:bg-primary-600 checked:ring-primary-600!',
            'hover:ring-neutral-400/70 focus-visible:outline-2 focus-visible:outline-offset-3 focus-visible:outline-primary-600',
            'dark:bg-neutral-800/70 dark:ring-neutral-700',
            'dark:hover:ring-neutral-600',
        ]}
    />
    <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white">
        <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-checked:opacity-100" />
    </svg>
</div>
