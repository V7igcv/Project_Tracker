<script setup>
import { Button } from '@/Components/ui/button';

import {
    FolderKanban,
    Plus,
} from 'lucide-vue-next';

const emit = defineEmits([
    'create',
]);
</script>

<template>

    <div
        class="rounded-xl border border-gray-200 bg-white shadow-sm"
    >

        <div
            class="flex flex-col items-center justify-center px-8 py-20"
        >

            <FolderKanban
                class="mb-5 h-16 w-16 text-gray-300"
            />

            <h2
                class="text-xl font-semibold text-gray-900"
            >
                No Phases Yet
            </h2>

            <p
                class="mt-2 max-w-md text-center text-sm leading-6 text-gray-500"
            >
                Start organizing this project by creating your first phase.
                Each phase will contain its own tasks and Gantt chart.
            </p>

            <Button
                class="mt-8 bg-[#A76A00] hover:bg-[#8A5A00]"
                @click="emit('create')"
            >

                <Plus class="mr-2 h-4 w-4" />

                Create First Phase

            </Button>

        </div>

    </div>

</template>