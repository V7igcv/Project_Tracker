<script setup>
import { ref } from 'vue';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

import { Button } from '@/Components/ui/button';

import {
    Plus,
} from 'lucide-vue-next';

import ProjectGrid from '@/Components/projects/ProjectGrid.vue';
import ProjectDialog from '@/Components/projects/ProjectDialog.vue';
import DeleteProjectDialog from '@/Components/projects/DeleteProjectDialog.vue';
import EmptyProjects from '@/Components/projects/EmptyProjects.vue';

/*
|--------------------------------------------------------------------------
| Temporary Dummy Data
|--------------------------------------------------------------------------
| Replace this with Inertia props later.
*/
const projects = ref([
    {
        id: 1,
        name: 'Project Tracker',
        description:
            'A web application for managing projects using Gantt Charts.',
        priority: 'High',
        progress: 65,
    },
    {
        id: 2,
        name: 'Capstone System',
        description:
            'GIS-based evacuation management system for Legazpi City.',
        priority: 'Medium',
        progress: 35,
    },
    {
        id: 3,
        name: 'Portfolio Website',
        description:
            'Personal website showcasing projects and experience.',
        priority: 'Low',
        progress: 100,
    },
]);

/*
|--------------------------------------------------------------------------
| Dialog States
|--------------------------------------------------------------------------
*/

const projectDialogOpen = ref(false);
const deleteDialogOpen = ref(false);

const dialogMode = ref('create');

const selectedProject = ref({
    name: '',
    priority: 'Medium',
    description: '',
});

/*
|--------------------------------------------------------------------------
| Open Dialogs
|--------------------------------------------------------------------------
*/

const openCreateDialog = () => {
    dialogMode.value = 'create';

    selectedProject.value = {
        name: '',
        priority: 'Medium',
        description: '',
    };

    projectDialogOpen.value = true;
};

const openEditDialog = (project) => {
    dialogMode.value = 'edit';

    selectedProject.value = {
        ...project,
    };

    projectDialogOpen.value = true;
};

const openDeleteDialog = (project) => {
    selectedProject.value = {
        ...project,
    };

    deleteDialogOpen.value = true;
};

/*
|--------------------------------------------------------------------------
| Placeholder Actions
|--------------------------------------------------------------------------
*/

const saveProject = (data) => {
    console.log(data);

    projectDialogOpen.value = false;
};

const deleteProject = () => {
    console.log('Delete:', selectedProject.value);

    deleteDialogOpen.value = false;
};

const openProject = (project) => {
    router.visit(`/projects/${project.id}`);
};
</script>

<template>

    <Head title="Projects" />

    <AuthenticatedLayout>

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