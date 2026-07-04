<script setup>
import {
    ChevronLeft,
    ChevronRight,
} from 'lucide-vue-next';

import { Button } from '@/Components/ui/button';

defineProps({
    monthLabel: {
        type: String,
        required: true,
    },
});

const emit = defineEmits([
    'previous',
    'next',
]);
</script>

<template>

<div class="flex w-full items-center justify-between gap-4">

    <Button
        size="icon"
        variant="ghost"
        @click="emit('previous')"
    >
        <ChevronLeft class="h-4 w-4" />
    </Button>

    <span
        class="flex-1 text-center text-sm font-semibold text-gray-700"
    >
        {{ monthLabel }}
    </span>

    <Button
        size="icon"
        variant="ghost"
        @click="emit('next')"
    >
        <ChevronRight class="h-4 w-4" />
    </Button>

</div>

</template>