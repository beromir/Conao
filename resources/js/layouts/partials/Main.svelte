<script lang="ts">
    import type { Snippet } from 'svelte';

    let { children }: { children: Snippet } = $props();
</script>

<main class="container mt-12">
    {@render children()}
</main>
