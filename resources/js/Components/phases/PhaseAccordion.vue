<script setup>
import { ref, computed, watch } from 'vue';

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
]);

const open = ref(true);

const progressColor = computed(() =>
    getProgressColor(props.progress)
);

const days = Array.from(
    { length: 31 },
    (_, i) => i + 1
);

const localTasks = ref([]);

watch(
    () => props.tasks,
    (value) => {
        localTasks.value = value.map(task => ({
            ...task,
            completedDays: task.completedDays ?? [],
        }));
    },
    {
        immediate: true,
    }
);

const isPlannedDay = (task, day) => {
    return day >= task.startDay && day <= task.endDay;
};

const isCompletedDay = (task, day) => {
    return task.completedDays.includes(day);
};

const toggleCompletedDay = (task, day) => {

    const index = task.completedDays.indexOf(day);

    if (index > -1) {

        task.completedDays.splice(index, 1);

    } else {

        task.completedDays.push(day);

    }

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
                        @click="emit('delete')"
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

        <div class="overflow-x-auto">

            <table class="min-w-max border-collapse">

                <!-- Header -->

                <thead>

                    <tr class="bg-gray-50">

                        <th
                            class="sticky left-0 z-10 border-b bg-gray-50 px-5 py-3 text-left font-semibold"
                        >
                            Task
                        </th>

                        <th
                            class="border-b px-4 py-3 text-left font-semibold"
                        >
                            Start
                        </th>

                        <th
                            class="border-b px-4 py-3 text-left font-semibold"
                        >
                            End
                        </th>

                        <th
                            :colspan="days.length"
                            class="border-b text-center font-semibold"
                        >
                            January 2026
                        </th>

                    </tr>

                    <tr>

                        <th></th>

                        <th></th>

                        <th></th>

                        <th
                            v-for="day in days"
                            :key="day"
                            class="h-10 w-10 border-b border-r text-xs text-gray-500"
                        >
                            {{ day }}
                        </th>

                    </tr>

                </thead>

                <!-- Body -->

                <tbody>

                    <tr
                        v-for="task in localTasks"
                        :key="task.id"
                    >

                        <td
                            class="sticky left-0 border-r bg-white px-5 py-4"
                        >

                            <div class="flex items-center gap-3">

                                <input
                                    type="checkbox"
                                >

                                {{ task.name }}

                            </div>

                        </td>

                        <td
                            class="border-r px-4"
                        >
                            {{ task.startDay }}
                        </td>

                        <td
                            class="border-r px-4"
                        >
                            {{ task.endDay }}
                        </td>

                        <td
                            v-for="day in days"
                            :key="day"
                            class="h-10 w-10 border-r border-b p-1"
                        >
                            <div
                                class="h-7 w-7 cursor-pointer rounded transition-all duration-150"

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
                            />
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </CardContent>

</Card>

</template>