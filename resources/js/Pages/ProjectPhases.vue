<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EmptyPhases from '@/Components/phases/EmptyPhases.vue';
import PhaseAccordion from '@/Components/phases/PhaseAccordion.vue';
import PhaseDialog from '@/Components/phases/PhaseDialog.vue';
import TaskDialog from '@/Components/phases/TaskDialog.vue';
import { Head, Link } from '@inertiajs/vue3';

import { Button } from '@/Components/ui/button';

import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/Components/ui/breadcrumb';

import {
    FolderKanban,
    Plus,
} from 'lucide-vue-next';

/*
|--------------------------------------------------------------------------
| Temporary Dummy Data
|--------------------------------------------------------------------------
| Replace with backend data later.
*/

const project = {
    id: 1,
    name: 'Project Tracker',
};

const phases = ref([
    {
        id: 1,
        name: 'Planning',
        progress: 45,

        tasks: [
            {
                id: 1,
                taskName: 'Requirements',

                startDate: '2026-01-02',
                endDate: '2026-01-06',

                isCompleted: false,

                progresses: [
                    { progressDate: '2026-01-02' },
                    { progressDate: '2026-01-03' },
                    { progressDate: '2026-01-04' },
                ],
            },

            {
                id: 2,
                taskName: 'UI Design',

                startDate: '2026-01-07',
                endDate: '2026-01-15',

                isCompleted: false,

                progresses: [
                    { progressDate: '2026-01-07' },
                    { progressDate: '2026-01-08' },
                ],
            },
        ]
    },

    {
        id: 2,
        name: 'Development',
        progress: 20,

        tasks: [
            {
                id: 3,
                taskName: 'Authentication',
                startDate: '2026-01-16',
                endDate: '2026-01-25',
                isCompleted: false,
                progresses: [
                    { progressDate: '2026-01-16' },
                    { progressDate: '2026-01-17' },
                ],
            },
        ],
    },
]);

const phaseDialogOpen = ref(false);

const phaseDialogMode = ref('create');

const selectedPhase = ref({
    phaseName: '',
    description: '',
});

const openCreatePhaseDialog = () => {

    phaseDialogMode.value = 'create';

    selectedPhase.value = {
        phaseName: '',
        description: '',
    };

    phaseDialogOpen.value = true;

};

const savePhase = (data) => {

    console.log(data);

    phaseDialogOpen.value = false;

};

const openEditPhaseDialog = (phase) => {

    phaseDialogMode.value = 'edit';

    selectedPhase.value = {
        ...phase,
    };

    phaseDialogOpen.value = true;

};

const taskDialogOpen = ref(false);

const taskDialogMode = ref('create');

const selectedTask = ref({
    taskName: '',
    startDate: '',
    endDate: '',
});

const openCreateTaskDialog = () => {

    taskDialogMode.value = 'create';

    selectedTask.value = {
        taskName: '',
        startDate: '',
        endDate: '',
    };

    taskDialogOpen.value = true;

};

const openEditTaskDialog = (task) => {

    taskDialogMode.value = 'edit';

    selectedTask.value = {
        ...task,
    };

    taskDialogOpen.value = true;

};

const saveTask = (data) => {

    console.log(data);

    taskDialogOpen.value = false;

};
</script>

<template>

    <Head :title="project.name" />

    <AuthenticatedLayout>

        <div class="py-8">

            <div class="mx-auto max-w-8xl px-6 lg:px-8">

                <!-- Breadcrumb -->

                <Breadcrumb class="mb-6">

                    <BreadcrumbList>

                        <BreadcrumbItem>

                            <BreadcrumbLink as-child>

                                <Link :href="route('projects')">
                                    Projects
                                </Link>

                            </BreadcrumbLink>

                        </BreadcrumbItem>

                        <BreadcrumbSeparator />

                        <BreadcrumbItem>

                            <BreadcrumbPage>
                                {{ project.name }}
                            </BreadcrumbPage>

                        </BreadcrumbItem>

                    </BreadcrumbList>

                </Breadcrumb>

                <!-- Header -->

                <div
                    class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
                >

                    <div>

                        <div class="flex items-center gap-3">

                            <FolderKanban
                                class="h-8 w-8 text-[#A76A00]"
                            />

                            <h1
                                class="text-3xl font-bold text-gray-900"
                            >
                                {{ project.name }}
                            </h1>

                        </div>

                        <p
                            class="mt-2 text-sm text-gray-500"
                        >
                            Project Phases
                        </p>

                    </div>

                    <Button
                        class="bg-[#A76A00] hover:bg-[#8A5A00]"
                        @click="openCreatePhaseDialog"
                    >

                        <Plus
                            class="mr-2 h-4 w-4"
                        />

                        Add Phase

                    </Button>

                </div>

                <!-- Placeholder -->

                <div
                    v-if="phases.length"
                    class="space-y-6"
                >

                    <PhaseAccordion
                        v-for="(phase, index) in phases"
                        :key="phase.id"
                        :phase-number="index + 1"
                        :phase-name="phase.name"
                        :progress="phase.progress"
                        :tasks="phase.tasks"

                        @edit="openEditPhaseDialog(phase)"
                        @add-task="openCreateTaskDialog"
                        @edit-task="openEditTaskDialog"
                    />

                </div>

                <EmptyPhases
                    v-else
                />

            </div>

        </div>

    </AuthenticatedLayout>

    <PhaseDialog
        :open="phaseDialogOpen"
        :mode="phaseDialogMode"
        :phase="selectedPhase"
        @update:open="phaseDialogOpen = $event"
        @save="savePhase"
    />

    <TaskDialog
        :open="taskDialogOpen"
        :mode="taskDialogMode"
        :task="selectedTask"
        @update:open="taskDialogOpen = $event"
        @save="saveTask"
    />

</template>