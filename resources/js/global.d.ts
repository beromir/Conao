import type { Task, TaskList, TaskListGroup } from '@/types';
import { route as routeFn } from 'ziggy-js';

declare global {
    let route: typeof routeFn;

    interface WindowEventMap {
        'taskLists.selected': CustomEvent<number | null>;
    }
}

declare module 'svelte/elements' {
    export interface SvelteWindowAttributes {
        'ontasks.create'?: (event: CustomEvent<Task>) => void;
        'ontasks.edit'?: (event: CustomEvent<Task>) => void;
        'ontasks.move'?: (event: CustomEvent<DragEvent>) => void;
        'ontaskLists.create'?: (event: CustomEvent) => void;
        'ontaskLists.edit'?: (event: CustomEvent<TaskList>) => void;
        'ontaskListGroups.create'?: (event: CustomEvent<number>) => void;
        'ontaskListGroups.edit'?: (event: CustomEvent<TaskListGroup>) => void;
        'ontaskLists.select'?: (event: CustomEvent<object>) => void;
    }
}

export {};
