<script setup>
import { TabsTrigger } from 'reka-ui';

defineProps({
    value: {
        type: String,
        required: true,
    },

    class: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <TabsTrigger
        :value="value"
        :class="[
            'inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all',
            'data-[state=active]:bg-white data-[state=active]:text-foreground',
            'data-[state=active]:shadow-sm',
            'focus-visible:outline-none focus-visible:ring-2',
            $props.class,
        ]"
    >
        <slot />
    </TabsTrigger>
</template>