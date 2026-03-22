import { router } from '@inertiajs/svelte';
import type { ClassValue } from 'svelte/elements';

export function join(...classes: (string | ClassValue | null | undefined | false)[]): string {
    return classes.filter(Boolean).join(' ');
}

export function refreshTaskLists() {
    router.reload({ only: ['taskLists'] });
}

export function dispatchCustomEvent(event: string, data: any = null) {
    if (data !== null) {
        window.dispatchEvent(new CustomEvent(event, { detail: data }));
    } else {
        window.dispatchEvent(new CustomEvent(event));
    }
}
