<script setup>
import ProjectCard from '@/Components/projects/ProjectCard.vue';

const props = defineProps({
    projects: {
        type: Array,
        default: () => [
            {
                id: 1,
                name: 'Project Tracker',
                description:
                    'A web application for managing projects using Gantt Charts, progress tracking, and deadlines.',
                priority: 'High',
                progress: 65,
            },
            {
                id: 2,
                name: 'Capstone System',
                description:
                    'A GIS-based evacuation management system for Legazpi City.',
                priority: 'Medium',
                progress: 35,
            },
            {
                id: 3,
                name: 'Portfolio Website',
                description:
                    'Personal portfolio showcasing projects and skills.',
                priority: 'Low',
                progress: 100,
            },
        ],
    },
});

const emit = defineEmits([
    'open',
    'edit',
    'delete',
]);
</script>

<template>

    <div
        class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3"
    >

        <ProjectCard
            v-for="project in projects"
            :key="project.id"
            :title="project.name"
            :description="project.description"
            :priority="project.priority"
            :progress="project.progress"
            @click="emit('open', project)"
            @edit="emit('edit', project)"
            @delete="emit('delete', project)"
        />

    </div>

</template>