<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import { Head } from '@inertiajs/vue3';

import StatCard from '@/Components/common/StatCard.vue';

import ProjectsTable from '@/Components/dashboard/ProjectsTable.vue';
import OverdueTasksTable from '@/Components/dashboard/OverdueTasksTable.vue';
import UpcomingDeadlinesTable from '@/Components/dashboard/UpcomingDeadlinesTable.vue';

import {
    FolderKanban,
    Clock3,
    CircleCheckBig,
} from 'lucide-vue-next';

const stats = ref({
    total_active_projects: 0,
    total_in_progress_projects: 0,
    total_completed_projects: 0,
    projects: [],
    search_projects: [],
    overdue_tasks: [],
    upcoming_deadlines: [],
});

const isLoading = ref(true);

const fetchDashboardData = async () => {
    try {
        const response = await axios.get(route('dashboard.index'));
        stats.value = response.data;
    } catch (error) {
        console.error('Error fetching dashboard data:', error);
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchDashboardData();
});
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout
        :projects="stats.search_projects"
    >

        <div class="py-8">

            <div class="mx-auto max-w-8xl px-6 lg:px-8">

                <!-- Header -->

                <div class="mb-8">

                    <h1 class="text-3xl font-bold text-gray-900">
                        Dashboard
                    </h1>

                    <p class="mt-2 text-sm text-gray-500">
                        Monitor your project progress, upcoming deadlines, and active tasks.
                    </p>

                </div>

                <!-- Statistics -->

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">

                    <StatCard
                        title="Active Projects"
                        :value="stats.total_active_projects"
                        :icon="FolderKanban"
                    />

                    <StatCard
                        title="In Progress Projects"
                        :value="stats.total_in_progress_projects"
                        :icon="Clock3"
                    />

                    <StatCard
                        title="Completed Projects"
                        :value="stats.total_completed_projects"
                        :icon="CircleCheckBig"
                    />

                </div>

                <!-- Projects -->

                <div class="mt-8">

                    <ProjectsTable :projects="stats.projects" />

                </div>

                <!-- Bottom Tables -->

                <div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-2">

                    <OverdueTasksTable :tasks="stats.overdue_tasks" />

                    <UpcomingDeadlinesTable :tasks="stats.upcoming_deadlines" />

                </div>

            </div>

        </div>

    </AuthenticatedLayout>

</template>