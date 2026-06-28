<script setup>
import { reactiveOmit } from "@vueuse/core";
import { Primitive } from "reka-ui";
import { cn } from "@/lib/utils";
import { badgeVariants } from ".";

import { computed } from 'vue';

const test = computed(() => badgeVariants({ variant: 'destructive' }));
console.log(test.value);

const props = defineProps({
  asChild: { type: Boolean, required: false },
  as: { type: null, required: false },
  variant: { type: null, required: false },
  class: {
    type: [Boolean, null, String, Object, Array],
    required: false,
    skipCheck: true,
  },
});

const delegatedProps = reactiveOmit(
    props,
    "class",
    "variant"
);
</script>

<template>
  <Primitive
    data-slot="badge"
    :data-variant="variant"
    :class="cn(badgeVariants({ variant }), props.class)"
    v-bind="delegatedProps"
  >
    <slot />
  </Primitive>
</template>
