import { workType } from '@/types/work'
export interface taskType {
    task_id: number;
    name: string;
    task_default_minute: number;
    task_is_everyday: number;
    task_sort_key: number;
    minute: number;
    works: workType[];
}