import { Model } from '@/enums';
import type { DropItem, DropTarget } from '@/types';
import { refreshTaskLists } from '@/utils';
import { router } from '@inertiajs/svelte';

export function setDragEventData(event: DragEvent, item: DropItem) {
    event.dataTransfer?.setData('item', JSON.stringify(item));
    event.dataTransfer?.setData(item.type.toLowerCase(), '');
}

export function handleMoveEvent(event: DragEvent, targetObject: any) {
    const item: DropItem | null = extractDropItem(event);
    const target: DropTarget = { type: targetObject.type, id: targetObject.id };

    if (!item) return;

    switch (item.type) {
        case Model.Task:
            moveTasks(item, target);
    }
}

export function extractDropItem(event: DragEvent): DropItem | null {
    const item = event.dataTransfer?.getData('item');

    if (!item) return null;

    return JSON.parse(item);
}

export function checkDropItemType(event: DragEvent, type: string): boolean {
    return (event.dataTransfer?.types.includes('item') && event.dataTransfer?.types.includes(type.toLowerCase())) ?? false;
}

function moveTasks(item: DropItem, target: DropTarget) {
    router.patch(
        route('tasks.move'),
        {
            id: item.id,
            targetType: target.type,
            targetId: target.id,
        },
        {
            onSuccess: () => refreshTaskLists(),
        },
    );
}
