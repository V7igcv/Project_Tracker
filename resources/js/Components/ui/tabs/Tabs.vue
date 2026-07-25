<script setup>
import { TabsRoot } from 'reka-ui';

const props = defineProps({
    defaultValue: {
        type: String,
        default: undefined,
    },

    modelValue: {
        type: String,
        default: undefined,
    },
});

const emit = defineEmits([
    'update:modelValue',
]);
</script>

<template>
    <TabsRoot
        :default-value="defaultValue"
        :model-value="modelValue"
        @update:model-value="emit('update:modelValue', $event)"
    >
        <slot />
    </TabsRoot>
</template>