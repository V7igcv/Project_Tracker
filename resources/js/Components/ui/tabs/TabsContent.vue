<script setup>
import { TabsContent } from 'reka-ui';

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
    <TabsContent
        :value="value"
        :class="[
            'mt-4 ring-offset-background focus-visible:outline-none',
            $props.class,
        ]"
    >
        <slot />
    </TabsContent>
</template>