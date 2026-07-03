<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EmptyPhases from '@/Components/phases/EmptyPhases.vue';
import PhaseAccordion from '@/Components/phases/PhaseAccordion.vue';
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
                name: 'Project Setup',
                startDay: '1',
                endDay: '5',
                completedDays: [1,2],
            },
            {
                id: 2,
                name: 'Requirement Analysis',
                startDay: '3',
                endDay: '10',
                completedDays: [3,4,5],
            },
        ],
    },

    {
        id: 2,
        name: 'Development',
        progress: 20,

        tasks: [
            {
                id: 3,
                name: 'Authentication',
                startDay: '8',
                endDay: '18',
            },
        ],
    },
]);

const openCreatePhaseDialog = () => {
    console.log('Open Add Phase Dialog');
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
                    />

                </div>

                <EmptyPhases
                    v-else
                />

            </div>

        </div>

    </AuthenticatedLayout>

</template>