import { Model } from '@/enums';

export interface Task {
    id: number;
    type: Model;
    title: string;
    state: string;
    plannedFor: string;
    deadline: string;
    plannedForLabel: string;
    deadlineLabel: string;
    checklist: ChecklistTask[];
    notes: string;
    parentListId: string;
    parentListType: string;
}

export interface ChecklistTask {
    id: string;
    title: string;
    state: 'open' | 'closed';
}

export interface List {
    id: number;
    type: Model;
}

export interface TaskList {
    id: number;
    type: Model;
    title: string;
    parentTaskListId: number;
    isArchived: boolean;
    tasksCount: number;
}

export interface TaskListGroup {
    id: number;
    type: Model;
    title: string;
    taskListId: number;
    tasks: Task[];
}

export interface DropItem {
    type: Model;
    id: number;
}

export interface DropTarget {
    type: Model;
    id: number;
}

export interface Settings {
    theme?: 'light' | 'dark';
}

export type Theme = 'light' | 'dark' | 'system';
