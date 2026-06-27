<script setup>
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/Components/ui/card';

defineProps({
    title: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Card class="border-0 shadow-sm pt-0">
        <CardHeader class="border-b bg-[#3D3D3D] px-6 py-4">
            <CardTitle
                class="text-xl font-semibold text-white"
            >
                {{ title }}
            </CardTitle>
        </CardHeader>

        <CardContent>
            <slot />
        </CardContent>
    </Card>
</template>