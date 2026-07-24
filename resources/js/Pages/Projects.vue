<script setup>
import axios from 'axios';
import { ref, watch } from 'vue';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

import { Button } from '@/Components/ui/button';

import { Plus } from 'lucide-vue-next';

import ProjectGrid from '@/Components/projects/ProjectGrid.vue';
import ProjectDialog from '@/Components/projects/ProjectDialog.vue';
import DeleteProjectDialog from '@/Components/projects/DeleteProjectDialog.vue';
import EmptyProjects from '@/Components/projects/EmptyProjects.vue';

const props = defineProps({
    projects: {
        type: Array,
        default: () => [],
    },
});

const normalizeProject = (project) => ({
    id: project.id,
    name: project.name ?? project.project_name ?? '',
    description: project.description ?? '',
    priority: project.priority ?? 'Medium',
    progress: Number(project.progress_percentage ?? project.progress ?? 0),
});

const projects = ref([]);

watch(
    () => props.projects,
    (value) => {
        projects.value = (value ?? []).map(normalizeProject);
    },
    {
        immediate: true,
    }
);

const projectDialogOpen = ref(false);
const deleteDialogOpen = ref(false);

const dialogMode = ref('create');

const selectedProject = ref({
    id: null,
    name: '',
    priority: 'Medium',
    description: '',
});

const projectErrors = ref({});

const resetSelectedProject = () => {
    selectedProject.value = {
        id: null,
        name: '',
        priority: 'Medium',
        description: '',
    };
};

const upsertProject = (project) => {
    const normalized = normalizeProject(project);
    const index = projects.value.findIndex((item) => item.id === normalized.id);

    if (index === -1) {
        projects.value.unshift(normalized);
        return;
    }

    projects.value.splice(index, 1, normalized);
};

const openCreateDialog = () => {
    dialogMode.value = 'create';
    projectErrors.value = {};
    resetSelectedProject();
    projectDialogOpen.value = true;
};

const openEditDialog = (project) => {
    dialogMode.value = 'edit';
    projectErrors.value = {};

    selectedProject.value = {
        ...project,
    };

    projectDialogOpen.value = true;
};

const openDeleteDialog = (project) => {
    projectErrors.value = {};

    selectedProject.value = {
        ...project,
    };

    deleteDialogOpen.value = true;
};

const saveProject = async (data) => {
    projectErrors.value = {};

    const payload = {
        project_name: data.name,
        priority: data.priority,
        description: data.description,
    };

    try {
        let response;

        if (dialogMode.value === 'create') {
            response = await axios.post(route('projects.store'), payload);
        } else {
            response = await axios.put(
                route('projects.update', selectedProject.value.id),
                payload
            );
        }

        upsertProject(response.data.data);
        projectDialogOpen.value = false;
    } catch (error) {
        if (error.response?.status === 422) {
            projectErrors.value = error.response.data.errors ?? {};
            return;
        }

        console.error(error);
    }
};

const deleteProject = async () => {
    try {
        await axios.delete(route('projects.destroy', selectedProject.value.id));

        projects.value = projects.value.filter(
            (project) => project.id !== selectedProject.value.id
        );

        deleteDialogOpen.value = false;
        resetSelectedProject();
    } catch (error) {
        console.error(error);
    }
};

const openProject = (project) => {
    router.visit(route('projects.phases', project.id));
};
</script>

<template>

    <Head title="Projects" />

    <AuthenticatedLayout
        :projects="props.projects"
    >

        <div class="py-8">

            <div class="mx-auto max-w-8xl px-6 lg:px-8">

                <!-- Header -->

                <div
                    class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
                >

                    <div>

                        <h1
                            class="text-3xl font-bold text-gray-900"
                        >
                            Project Overview
                        </h1>

                        <p
                            class="mt-2 text-sm text-gray-500"
                        >
                            Manage your projects, organize phases,
                            and monitor overall progress.
                        </p>

                    </div>

                    <Button
                        class="bg-[#A76A00] hover:bg-[#8A5A00]"
                        @click="openCreateDialog"
                    >

                        <Plus
                            class="mr-2 h-4 w-4"
                        />

                        Add Project

                    </Button>

                </div>

                <!-- Content -->

                <ProjectGrid
                    v-if="projects.length > 0"
                    :projects="projects"
                    @open="openProject"
                    @edit="openEditDialog"
                    @delete="openDeleteDialog"
                />

                <EmptyProjects
                    v-else
                    @create="openCreateDialog"
                />

            </div>

        </div>

        <!-- Project Dialog -->

        <ProjectDialog
            :open="projectDialogOpen"
            :mode="dialogMode"
            :project="selectedProject"
            :errors="projectErrors"
            @update:open="projectDialogOpen = $event"
            @save="saveProject"
        />

        <!-- Delete Dialog -->

        <DeleteProjectDialog
            :open="deleteDialogOpen"
            :project-name="selectedProject.name"
            @update:open="deleteDialogOpen = $event"
            @confirm="deleteProject"
        />

    </AuthenticatedLayout>

</template>