<script setup>
import axios from 'axios';
import { ref, computed, watch } from 'vue';
import GanttNavigator from './GanttNavigator.vue';
import DeleteDialog from '@/Components/phases/DeleteDialog.vue';

import {
    ChevronDown,
    ChevronRight,
    Plus,
    MoreVertical,
    Pencil,
    Trash2,
    FolderKanban,
} from 'lucide-vue-next';

import {
    Card,
    CardContent,
} from '@/Components/ui/card';

import { Progress } from '@/Components/ui/progress';

import { Button } from '@/Components/ui/button';

import {
    DropdownMenu,
    DropdownMenuTrigger,
    DropdownMenuContent,
    DropdownMenuItem,
} from '@/Components/ui/dropdown-menu';

import { getProgressColor } from '@/lib/projectUtils';

const props = defineProps({
    projectId: {
        type: Number,
        required: true,
    },

    phaseId: {
        type: Number,
        required: true,
    },

    phaseNumber: Number,

    phaseName: String,

    progress: {
        type: Number,
        default: 0,
    },

    tasks: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits([
    'add-task',
    'edit',
    'delete',
    'edit-task',
    'delete-task',
    'progress-toggle',
    'update-task-complete',
]);

const open = ref(false);

const progressColor = computed(() =>
    getProgressColor(props.progress)
);

const leftSectionClass =
    'grid w-[500px] shrink-0 grid-cols-[minmax(0,1fr)_120px_120px]';

const parseDate = (value) => {
    if (!value) {
        return null;
    }

    const date = new Date(value);

    return Number.isNaN(date.getTime()) ? null : date;
};

const formatDisplayDate = (value) => {
    const date = parseDate(value);

    if (!date) {
        return '';
    }

    return new Intl.DateTimeFormat('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    }).format(date);
};

const currentMonth = ref(new Date());

const monthLabel = computed(() => {

    return currentMonth.value.toLocaleDateString(
        'en-US',
        {
            month: 'long',
            year: 'numeric',
        }
    );

});

const days = computed(() => {

    const totalDays = new Date(
        currentMonth.value.getFullYear(),
        currentMonth.value.getMonth() + 1,
        0
    ).getDate();

    return Array.from(
        { length: totalDays },
        (_, i) =>
            new Date(
                currentMonth.value.getFullYear(),
                currentMonth.value.getMonth(),
                i + 1
            )
    );

});

const previousMonth = () => {
    currentMonth.value = new Date(
        currentMonth.value.getFullYear(),
        currentMonth.value.getMonth() - 1,
        1
    );
    // Mark that user manually changed the month
    currentMonth.value._userChanged = true;
};

const nextMonth = () => {
    currentMonth.value = new Date(
        currentMonth.value.getFullYear(),
        currentMonth.value.getMonth() + 1,
        1
    );
    // Mark that user manually changed the month
    currentMonth.value._userChanged = true;
};

const localTasks = ref([]);

const getEarliestTaskDate = (tasks) => {
    const dates = tasks
        .flatMap((task) => [task.startDate, task.endDate])
        .map(parseDate)
        .filter(Boolean)
        .sort((left, right) => left.getTime() - right.getTime());

    return dates[0] ?? null;
};

const toggleTaskComplete = (task) => {
    task.isCompleted = !task.isCompleted;
    // Emit an event to update the parent
    emit('update-task-complete', task);
};

watch(
    () => props.tasks,
    (value) => {
        // Always update localTasks from props
        localTasks.value = value.map(task => ({
            ...task,
        }));

        // Only set current month to earliest task date if we haven't manually changed it
        if (!currentMonth.value || !currentMonth.value._userChanged) {
            const earliestTaskDate = getEarliestTaskDate(value);
            if (earliestTaskDate) {
                currentMonth.value = new Date(
                    earliestTaskDate.getFullYear(),
                    earliestTaskDate.getMonth(),
                    1
                );
            }
        }
    },
    {
        immediate: true,
        deep: true,
    }
);

const toggleCompletedDay = (task, day) => {
    const dateString = formatDate(day);

    const index = task.progresses.findIndex(
        (progress) => progress.progressDate === dateString
    );

    const wasCompleted = index > -1;

    // Create a new progresses array to ensure reactivity
    let newProgresses;
    if (wasCompleted) {
        newProgresses = task.progresses.filter(
            (progress) => progress.progressDate !== dateString
        );
    } else {
        newProgresses = [
            ...task.progresses,
            { progressDate: dateString },
        ];
    }
    
    task.progresses = newProgresses;

    // Emit with the correct isCompleted value
    emit('progress-toggle', task, dateString, wasCompleted);
};

const formatDate = (date) => {
    if (!date || Number.isNaN(date.getTime())) {
        return '';
    }

    return (
        `${date.getFullYear()}-` +
        `${String(date.getMonth() + 1).padStart(2, '0')}-` +
        `${String(date.getDate()).padStart(2, '0')}`
    );
};

const isPlannedDay = (task, day) => {

    const dateString = formatDate(day);

    const taskStartDate = formatDate(parseDate(task.startDate));
    const taskEndDate = formatDate(parseDate(task.endDate));

    return (
        dateString >= taskStartDate &&
        dateString <= taskEndDate
    );

};

const isCompletedDay = (task, day) => {

    const dateString = formatDate(day);

    return task.progresses.some(
        progress => progress.progressDate === dateString
    );

};

const deleteDialogOpen = ref(false);

const deleteType = ref('phase');

const deleteTitle = ref('');
const deleteTask = ref(null);

const openDeletePhase = () => {

    deleteType.value = 'phase';

    deleteTitle.value = props.phaseName;

    deleteDialogOpen.value = true;

};

const openDeleteTask = (task) => {

    deleteType.value = 'task';

    deleteTitle.value = task.taskName;
    deleteTask.value = task;

    deleteDialogOpen.value = true;

};

const confirmDelete = () => {
    if (deleteType.value === 'phase') {
        emit('delete');
    } else {
        emit('delete-task', deleteTask.value);
    }

    deleteDialogOpen.value = false;

};
</script>

<template>

<Card class="overflow-hidden">

    <!-- Header -->

    <div
        class="flex cursor-pointer items-start justify-between p-6"
        @click="open = !open"
    >

        <div class="flex flex-1 gap-4">

            <button class="mt-1">

                <ChevronDown
                    v-if="open"
                    class="h-5 w-5 text-gray-500"
                />

                <ChevronRight
                    v-else
                    class="h-5 w-5 text-gray-500"
                />

            </button>

            <div class="flex-1">

                <h2
                    class="text-xl font-semibold text-gray-900"
                >
                    {{ phaseName }}
                </h2>

                <div class="mt-4">

                    <Progress
                        :model-value="progress"
                        :indicator-class="progressColor"
                    />

                    <div
                        class="mt-2 flex justify-between text-sm"
                    >

                        <span class="font-medium">
                            {{ progress }}%
                        </span>

                        <span class="text-gray-500">
                            Phase Progress
                        </span>

                    </div>

                </div>

            </div>

        </div>

        <!-- Actions -->

        <div
            class="ml-6 flex items-center gap-2"
            @click.stop
        >

            <Button
                size="icon"
                variant="outline"
                @click="emit('add-task')"
            >

                <Plus class="h-4 w-4" />

            </Button>

            <DropdownMenu>

                <DropdownMenuTrigger as-child>

                    <Button
                        size="icon"
                        variant="ghost"
                    >

                        <MoreVertical
                            class="h-4 w-4"
                        />

                    </Button>

                </DropdownMenuTrigger>

                <DropdownMenuContent align="end">

                    <DropdownMenuItem
                        @click="emit('edit')"
                    >

                        <Pencil
                            class="mr-2 h-4 w-4"
                        />

                        Edit

                    </DropdownMenuItem>

                    <DropdownMenuItem
                        class="text-red-600"
                        @click="openDeletePhase"
                    >

                        <Trash2
                            class="mr-2 h-4 w-4"
                        />

                        Delete

                    </DropdownMenuItem>

                </DropdownMenuContent>

            </DropdownMenu>

        </div>

    </div>

    <!-- Body -->

    <CardContent
        v-show="open"
        class="border-t bg-white p-0"
    >

        <div
            v-if="localTasks.length === 0"
            class="flex flex-col items-center justify-center p-8 py-12 text-center"
        >
            <FolderKanban
                class="mb-3 h-10 w-10 text-gray-300"
            />
            <h3 class="text-base font-semibold text-gray-900">
                No Tasks Yet
            </h3>
            <p class="mt-1 max-w-sm text-xs text-gray-500">
                Start adding tasks to this phase to organize work items and track progress on the Gantt chart.
            </p>
            <Button
                size="sm"
                variant="outline"
                class="mt-4 border-[#A76A00] text-[#A76A00] hover:bg-[#A76A00] hover:text-white"
                @click="emit('add-task')"
            >
                <Plus class="mr-1.5 h-3.5 w-3.5" />
                Add First Task
            </Button>
        </div>

        <template v-else>

            <div class="border-b bg-gray-50">

                <div class="flex">

                    <div :class="leftSectionClass"></div>

                    <div class="min-w-0 flex-1 px-4 py-3">

                        <GanttNavigator
                            :month-label="monthLabel"
                            @previous="previousMonth"
                            @next="nextMonth"
                        />

                    </div>

                </div>

            </div>

            <div class="flex items-stretch">

                <div class="w-[500px] shrink-0 border-r border-gray-200 bg-white">

                    <div class="grid h-10 grid-cols-[minmax(0,1fr)_120px_120px] border-b bg-gray-50">

                        <div class="border-r px-5 py-3 text-left font-semibold">
                            Task
                        </div>

                        <div class="border-r px-4 py-3 text-left font-semibold whitespace-nowrap">
                            Start
                        </div>

                        <div class="px-4 py-3 text-left font-semibold whitespace-nowrap">
                            End
                        </div>

                    </div>

                    <div
                        v-for="task in localTasks"
                        :key="task.id"
                        class="grid h-[56px] grid-cols-[minmax(0,1fr)_120px_120px] border-b"
                    >

                        <div class="border-r px-5 py-4">

                            <div class="flex min-w-0 items-center gap-3">

                                <input 
                                    type="checkbox"
                                    :checked="task.isCompleted"
                                    @change="toggleTaskComplete(task)"
                                >

                                <DropdownMenu>

                                    <DropdownMenuTrigger as-child>

                                        <button
                                            class="rounded-md p-1 transition hover:bg-gray-100"
                                            @click.stop
                                        >

                                            <MoreVertical class="h-4 w-4 text-gray-500" />

                                        </button>

                                    </DropdownMenuTrigger>

                                    <DropdownMenuContent align="start">

                                        <DropdownMenuItem
                                            @click.stop="emit('edit-task', task)"
                                        >

                                            <Pencil class="mr-2 h-4 w-4" />

                                            Edit

                                        </DropdownMenuItem>

                                        <DropdownMenuItem
                                            class="text-red-600"
                                            @click.stop="openDeleteTask(task)"
                                        >

                                            <Trash2 class="mr-2 h-4 w-4" />

                                            Delete

                                        </DropdownMenuItem>

                                    </DropdownMenuContent>

                                </DropdownMenu>

                                <span class="min-w-0 truncate">
                                    {{ task.taskName }}
                                </span>

                            </div>

                        </div>

                        <div class="border-r px-4 py-4 whitespace-nowrap">
                            {{ formatDisplayDate(task.startDate) }}
                        </div>

                        <div class="px-4 py-4 whitespace-nowrap">
                            {{ formatDisplayDate(task.endDate) }}
                        </div>

                    </div>

                </div>

                <div class="min-w-0 flex-1 overflow-x-auto">

                    <div class="min-w-max">

                        <div class="flex h-10 border-b bg-gray-50">

                            <div
                                v-for="day in days"
                                :key="day"
                                class="flex h-full w-8 shrink-0 items-center justify-center border-r text-xs text-gray-500"
                            >
                                {{ day.getDate() }}
                            </div>

                        </div>

                        <div
                            v-for="task in localTasks"
                            :key="task.id"
                            class="flex h-[56px] items-stretch border-b bg-white"
                        >

                            <div class="flex">

                                <div
                                    v-for="day in days"
                                    :key="day"
                                    class="flex w-8 shrink-0 items-center justify-center self-stretch border-r transition-all duration-150"
                                    :class="{

                                        /*
                                        Completed Progress
                                        */

                                        'bg-[#A76A00] hover:bg-[#8A5A00]':
                                            isCompletedDay(task, day),

                                        /*
                                        Planned
                                        */

                                        'bg-[#E7C87A] hover:bg-[#D9B14A]':
                                            !isCompletedDay(task, day) &&
                                            isPlannedDay(task, day),

                                        /*
                                        Outside schedule
                                        */

                                        'hover:bg-gray-100':
                                            !isCompletedDay(task, day) &&
                                            !isPlannedDay(task, day),

                                    }"
                                    @click="toggleCompletedDay(task, day)"
                                >
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </template>

    </CardContent>

</Card>

    <DeleteDialog
        :open="deleteDialogOpen"
        :title="deleteTitle"
        :type="deleteType"
        @update:open="deleteDialogOpen = $event"
        @confirm="confirmDelete"
    />

</template>