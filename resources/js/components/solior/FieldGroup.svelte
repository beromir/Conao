<script lang="ts">
    import type { Snippet } from 'svelte';

    let { children }: { children: Snippet } = $props();
</script>

<div class="space-y-6 [&>button[type=submit]]:mt-12">
    {@render children?.()}
</div>
