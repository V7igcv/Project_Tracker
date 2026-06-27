<script setup>
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
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>

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
                        :value="8"
                        :icon="FolderKanban"
                    />

                    <StatCard
                        title="In Progress Projects"
                        :value="5"
                        :icon="Clock3"
                    />

                    <StatCard
                        title="Completed Projects"
                        :value="12"
                        :icon="CircleCheckBig"
                    />

                </div>

                <!-- Projects -->

                <div class="mt-8">

                    <ProjectsTable />

                </div>

                <!-- Bottom Tables -->

                <div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-2">

                    <OverdueTasksTable />

                    <UpcomingDeadlinesTable />

                </div>

            </div>

        </div>

    </AuthenticatedLayout>

</template>