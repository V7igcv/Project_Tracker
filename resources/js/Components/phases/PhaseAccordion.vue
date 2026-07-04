<script setup>
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
]);

const open = ref(true);

const progressColor = computed(() =>
    getProgressColor(props.progress)
);

const leftSectionClass =
    'grid w-[500px] shrink-0 grid-cols-[minmax(0,1fr)_120px_120px]';

const currentMonth = ref(new Date(2026, 0, 1));

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

};

const nextMonth = () => {

    currentMonth.value = new Date(
        currentMonth.value.getFullYear(),
        currentMonth.value.getMonth() + 1,
        1
    );

};

const localTasks = ref([]);

watch(
    () => props.tasks,
    (value) => {

        localTasks.value = value.map(task => ({
            ...task,
        }));

    },
    {
        immediate: true,
    }
);

const toggleCompletedDay = (task, day) => {

    const dateString = formatDate(day);

    const index = task.progresses.findIndex(
        progress => progress.progressDate === dateString
    );

    if (index > -1) {

        task.progresses.splice(index, 1);

    } else {

        task.progresses.push({
            progressDate: dateString,
        });

    }

};

const formatDate = (date) => {
    return (
        `${date.getFullYear()}-` +
        `${String(date.getMonth() + 1).padStart(2, '0')}-` +
        `${String(date.getDate()).padStart(2, '0')}`
    );
};

const isPlannedDay = (task, day) => {

    const dateString = formatDate(day);

    return (
        dateString >= task.startDate &&
        dateString <= task.endDate
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

const openDeletePhase = () => {

    deleteType.value = 'phase';

    deleteTitle.value =
        `Phase ${props.phaseNumber}: ${props.phaseName}`;

    deleteDialogOpen.value = true;

};

const openDeleteTask = (task) => {

    deleteType.value = 'task';

    deleteTitle.value = task.taskName;

    deleteDialogOpen.value = true;

};

const confirmDelete = () => {

    console.log(
        'Delete',
        deleteType.value,
        deleteTitle.value
    );

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
                    Phase {{ phaseNumber }}:
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

                            <input type="checkbox">

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
                        {{ task.startDate }}
                    </div>

                    <div class="px-4 py-4 whitespace-nowrap">
                        {{ task.endDate }}
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