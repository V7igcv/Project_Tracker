<script setup>
import axios from 'axios';
import { computed, ref, watch } from 'vue';
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

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },

    projects: {
        type: Array,
        default: () => [],
    },
});

const normalizeDateString = (value) => {
    if (!value) {
        return '';
    }

    if (typeof value === 'string') {
        return value.slice(0, 10);
    }

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) {
        return '';
    }

    return date.toISOString().slice(0, 10);
};

const normalizeTask = (task) => ({
    id: task.id,
    taskName: task.task_name ?? task.taskName ?? '',
    startDate: task.start_date ?? task.startDate ?? '',
    endDate: task.end_date ?? task.endDate ?? '',
    isCompleted: Boolean(task.is_completed ?? task.isCompleted ?? false),
    progresses: (task.progresses ?? task.task_progresses ?? []).map((progress) => ({
        progressDate: normalizeDateString(progress.progress_date ?? progress.progressDate),
    })),
});

const normalizePhase = (phase) => ({
    id: phase.id,
    phaseNumber: phase.phase_number ?? phase.phaseNumber ?? 0,
    phaseName: phase.phase_name ?? phase.phaseName ?? '',
    description: phase.description ?? '',
    progress: Number(phase.progress_percentage ?? phase.progress ?? 0),
    tasks: (phase.tasks ?? []).map(normalizeTask),
});

const normalizeProgresses = (progresses) =>
    (progresses ?? []).map((progress) => ({
        progressDate: normalizeDateString(progress.progress_date ?? progress.progressDate),
    }));

const calculatePhaseProgress = (tasks) => {
    const totalTasks = tasks.length;

    if (totalTasks === 0) {
        return 0;
    }

    const completedTasks = tasks.filter((task) => task.isCompleted).length;

    return Math.round((completedTasks / totalTasks) * 10000) / 100;
};

const project = computed(() => ({
    id: props.project.id,
    name: props.project.project_name ?? props.project.name ?? '',
    description: props.project.description ?? '',
}));

const phases = ref([]);

watch(
    () => props.project,
    (value) => {
        phases.value = (value?.phases ?? []).map(normalizePhase);
    },
    {
        immediate: true,
    }
);

const phaseDialogOpen = ref(false);
const phaseDialogMode = ref('create');
const phaseErrors = ref({});
const selectedPhase = ref({
    id: null,
    phaseName: '',
    description: '',
});

const taskDialogOpen = ref(false);
const taskDialogMode = ref('create');
const selectedTask = ref({
    id: null,
    taskName: '',
    startDate: '',
    endDate: '',
});

const taskErrors = ref({});
const selectedTaskPhaseId = ref(null);

const openCreatePhaseDialog = () => {
    phaseDialogMode.value = 'create';
    phaseErrors.value = {};
    selectedPhase.value = {
        id: null,
        phaseName: '',
        description: '',
    };
    phaseDialogOpen.value = true;
};

const openEditPhaseDialog = (phase) => {
    phaseDialogMode.value = 'edit';
    phaseErrors.value = {};
    selectedPhase.value = {
        ...phase,
    };
    phaseDialogOpen.value = true;
};

const openCreateTaskDialog = (phaseId = null) => {
    taskDialogMode.value = 'create';
    taskErrors.value = {};
    selectedTaskPhaseId.value = phaseId;
    selectedTask.value = {
        id: null,
        taskName: '',
        startDate: '',
        endDate: '',
    };
    taskDialogOpen.value = true;
};

const openCreateTaskDialogForPhase = (phase) => {
    openCreateTaskDialog(phase.id);
};

const openEditTaskDialog = (phase, task) => {
    taskDialogMode.value = 'edit';
    taskErrors.value = {};
    selectedTaskPhaseId.value = phase.id;
    selectedTask.value = {
        ...task,
    };
    taskDialogOpen.value = true;
};

const findPhaseById = (phaseId) => phases.value.find((phase) => phase.id === phaseId);

const updatePhaseProgress = (phase) => {
    phase.progress = calculatePhaseProgress(phase.tasks);
};

const updateProjectProgress = () => {
    phases.value.forEach((phase) => updatePhaseProgress(phase));
};

const upsertPhase = (phase) => {
    const normalized = normalizePhase(phase);
    const index = phases.value.findIndex((item) => item.id === normalized.id);

    if (index === -1) {
        phases.value.push(normalized);
    } else {
        phases.value.splice(index, 1, normalized);
    }

    phases.value.sort((a, b) => {
        if (a.phaseNumber !== b.phaseNumber) {
            return a.phaseNumber - b.phaseNumber;
        }
        return a.id - b.id;
    });
};

const savePhase = async (data) => {
    phaseErrors.value = {};

    const payload = {
        phase_name: data.phaseName,
        description: data.description,
    };

    try {
        let response;

        if (phaseDialogMode.value === 'create') {
            response = await axios.post(
                route('phases.store', project.value.id),
                payload
            );
        } else {
            response = await axios.put(
                route('phases.update', [project.value.id, selectedPhase.value.id]),
                payload
            );
        }

        upsertPhase(response.data.data);
        phaseDialogOpen.value = false;
    } catch (error) {
        if (error.response?.status === 422) {
            phaseErrors.value = error.response.data.errors ?? {};
            return;
        }

        console.error(error);
    }
};

const deletePhase = async (phase) => {
    try {
        await axios.delete(route('phases.destroy', [project.value.id, phase.id]));
        phases.value = phases.value.filter((item) => item.id !== phase.id);
    } catch (error) {
        console.error(error);
    }
};

const saveTask = async (data) => {
    taskErrors.value = {};

    const payload = {
        task_name: data.taskName,
        start_date: data.startDate,
        end_date: data.endDate,
    };

    try {
        let response;

        if (taskDialogMode.value === 'create') {
            response = await axios.post(
                route('tasks.store', [project.value.id, selectedTaskPhaseId.value]),
                payload
            );
        } else {
            response = await axios.put(
                route('tasks.update', [project.value.id, selectedTaskPhaseId.value, selectedTask.value.id]),
                payload
            );
        }

        const phase = findPhaseById(selectedTaskPhaseId.value);

        if (phase) {
            const normalizedTask = normalizeTask(response.data.data);
            const index = phase.tasks.findIndex((task) => task.id === normalizedTask.id);

            if (index === -1) {
                phase.tasks.push(normalizedTask);
            } else {
                phase.tasks.splice(index, 1, normalizedTask);
            }

            phase.tasks.sort((a, b) => {
                const dateA = new Date(a.startDate || 0).getTime();
                const dateB = new Date(b.startDate || 0).getTime();
                if (dateA !== dateB) {
                    return dateA - dateB;
                }
                return a.id - b.id;
            });

            phase.tasks = [...phase.tasks];

            updatePhaseProgress(phase);
        }

        taskDialogOpen.value = false;
    } catch (error) {
        if (error.response?.status === 422) {
            taskErrors.value = error.response.data.errors ?? {};
            return;
        }

        console.error(error);
    }
};

const deleteTask = async (phase, task) => {
    try {
        await axios.delete(
            route('tasks.destroy', [project.value.id, phase.id, task.id])
        );

        phase.tasks = phase.tasks.filter((item) => item.id !== task.id);
        phase.tasks = [...phase.tasks];
        updatePhaseProgress(phase);
        updateProjectProgress();
    } catch (error) {
        console.error(error);
    }
};

const handleProgressToggle = async (phase, task, progressDate, isCompleted) => {
    const normalizedTask = phase.tasks.find((item) => item.id === task.id);

    if (!normalizedTask) {
        return;
    }

    const previousProgresses = [...normalizedTask.progresses];

    try {
        const response = await axios.post(
            route('progresses.toggle', [
                project.value.id,
                phase.id,
                task.id,
            ]),
            {
                progress_date: progressDate,
            }
        );

        // Update with server response
        if (response.data?.data?.progresses) {
            // Map the progresses properly
            const newProgresses = response.data.data.progresses.map((progress) => ({
                progressDate: progress.progress_date ?? progress.progressDate ?? '',
            }));
            
            // Update the task's progresses
            normalizedTask.progresses = newProgresses;
            
            // Force reactivity by creating a new array for the phase's tasks
            phase.tasks = phase.tasks.map(t => 
                t.id === normalizedTask.id ? { ...normalizedTask } : t
            );
            
            // Update progress
            updatePhaseProgress(phase);
            updateProjectProgress();
        }
        
    } catch (error) {
        // Revert on error
        normalizedTask.progresses = previousProgresses;
        phase.tasks = phase.tasks.map(t => 
            t.id === normalizedTask.id ? { ...normalizedTask } : t
        );
        console.error(error);
    }
};

const handleTaskComplete = async (phase, task) => {
    // Update the task's isCompleted status
    // This should trigger a save to the database
    try {
        const response = await axios.put(
            route('tasks.update', [project.value.id, phase.id, task.id]),
            {
                task_name: task.taskName,
                start_date: task.startDate,
                end_date: task.endDate,
                is_completed: task.isCompleted,
            }
        );
        
        // Update the task with the server response
        const normalizedTask = normalizeTask(response.data.data);
        const index = phase.tasks.findIndex((t) => t.id === normalizedTask.id);
        
        if (index !== -1) {
            phase.tasks[index] = normalizedTask;
            phase.tasks = [...phase.tasks];
            updatePhaseProgress(phase);
            updateProjectProgress();
        }
    } catch (error) {
        console.error(error);
        // Revert the change
        task.isCompleted = !task.isCompleted;
    }
};
</script>

<template>

    <Head :title="project.name" />

    <AuthenticatedLayout
        :projects="projects"
    >

        <div class="py-8">

            <div class="mx-auto max-w-8xl px-6 lg:px-8">

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

                <div
                    class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
                >

                    <div>

                        <div class="flex items-center gap-3">

                            <FolderKanban class="h-8 w-8 text-[#A76A00]" />

                            <h1 class="text-3xl font-bold text-gray-900">
                                {{ project.name }}
                            </h1>

                        </div>

                        <p class="mt-2 text-sm text-gray-500">
                            {{ project.description }}
                        </p>

                    </div>

                    <Button
                        class="bg-[#A76A00] hover:bg-[#8A5A00]"
                        @click="openCreatePhaseDialog"
                    >

                        <Plus class="mr-2 h-4 w-4" />

                        Add Phase

                    </Button>

                </div>

                <div
                    v-if="phases.length"
                    class="space-y-6"
                >

                    <PhaseAccordion
                        v-for="(phase, index) in phases"
                        :key="phase.id"
                        :project-id="project.id"
                        :phase-id="phase.id"
                        :phase-number="phase.phaseNumber || index + 1"
                        :phase-name="phase.phaseName"
                        :progress="phase.progress"
                        :tasks="phase.tasks"
                        @edit="openEditPhaseDialog(phase)"
                        @add-task="openCreateTaskDialogForPhase(phase)"
                        @edit-task="(task) => openEditTaskDialog(phase, task)"
                        @delete="deletePhase(phase)"
                        @delete-task="(task) => deleteTask(phase, task)"
                        @progress-toggle="(task, progressDate, isCompleted) => handleProgressToggle(phase, task, progressDate, isCompleted)"
                        @update-task-complete="(task) => handleTaskComplete(phase, task)"
                    />

                </div>

                <EmptyPhases v-else @create="openCreatePhaseDialog" />

            </div>

        </div>

    </AuthenticatedLayout>

    <PhaseDialog
        :open="phaseDialogOpen"
        :mode="phaseDialogMode"
        :phase="selectedPhase"
        :errors="phaseErrors"
        @update:open="phaseDialogOpen = $event"
        @save="savePhase"
    />

    <TaskDialog
        :open="taskDialogOpen"
        :mode="taskDialogMode"
        :task="selectedTask"
        :errors="taskErrors"
        @update:open="taskDialogOpen = $event"
        @save="saveTask"
    />

</template>
