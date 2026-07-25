<script setup>
import { TabsList } from 'reka-ui';

defineProps({
    class: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <TabsList
        :class="[
            'inline-flex h-10 items-center justify-center rounded-md bg-muted p-1 text-muted-foreground',
            $props.class,
        ]"
    >
        <slot />
    </TabsList>
</template>