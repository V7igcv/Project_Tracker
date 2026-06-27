<script setup>
import { Button } from '@/Components/ui/button';
import { FolderKanban, Plus } from 'lucide-vue-next';

const emit = defineEmits([
    'create',
]);
</script>

<template>

    <div
        class="flex flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 bg-white py-20 text-center shadow-sm"
    >

        <!-- Icon -->

        <div
            class="mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-[#A76A00]/10"
        >
            <FolderKanban
                class="h-10 w-10 text-[#A76A00]"
            />
        </div>

        <!-- Title -->

        <h2
            class="text-2xl font-bold text-gray-900"
        >
            No Projects Yet
        </h2>

        <!-- Description -->

        <p
            class="mt-3 max-w-md text-sm leading-6 text-gray-500"
        >
            Start organizing your work by creating your first project.
            Track project phases, monitor progress, and stay on top of deadlines
            with an interactive Gantt Chart.
        </p>

        <!-- Button -->

        <Button
            class="mt-8 bg-[#A76A00] hover:bg-[#8A5A00]"
            @click="emit('create')"
        >

            <Plus class="mr-2 h-4 w-4" />

            Create New Project

        </Button>

    </div>

</template>