<script setup>
import { Card, CardContent } from '@/Components/ui/card';

defineProps({
    title: {
        type: String,
        required: true,
    },

    value: {
        type: [String, Number],
        required: true,
    },

    icon: {
        type: Object,
        required: true,
    },

    iconBg: {
        type: String,
        default: 'bg-[#A76A00]/10',
    },

    iconColor: {
        type: String,
        default: 'text-[#A76A00]',
    },
});
</script>

<template>
    <Card class="border-0 shadow-sm transition-all duration-200 hover:shadow-md">
        <CardContent class="flex items-center justify-between px-6 py-2">

            <div>
                <p class="text-sm font-medium text-gray-500">
                    {{ title }}
                </p>

                <h2 class="mt-2 text-3xl font-bold text-gray-900">
                    {{ value }}
                </h2>
            </div>

            <div
                class="flex h-14 w-14 items-center justify-center rounded-xl"
                :class="iconBg"
            >
                <component
                    :is="icon"
                    class="h-7 w-7"
                    :class="iconColor"
                />
            </div>

        </CardContent>
    </Card>
</template>