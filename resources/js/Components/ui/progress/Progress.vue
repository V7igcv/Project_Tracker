<script setup>
import { reactiveOmit } from "@vueuse/core";
import { ProgressIndicator, ProgressRoot } from "reka-ui";
import { cn } from "@/lib/utils";
import { computed } from "vue";

const props = defineProps({
  modelValue: { type: [Number, null], required: false, default: 0 },
  max: { type: Number, required: false },
  getValueLabel: { type: Function, required: false },
  getValueText: { type: Function, required: false },
  asChild: { type: Boolean, required: false },
  as: { type: null, required: false },
  indicatorClass: { 
    type: String, 
    required: false,
    default: 'bg-primary' 
  },
  class: {
    type: [Boolean, null, String, Object, Array],
    required: false,
    skipCheck: true,
  },
});

const delegatedProps = reactiveOmit(props, "class", "indicatorClass");
</script>

<template>
  <ProgressRoot
    data-slot="progress"
    v-bind="delegatedProps"
    :class="
      cn(
        'bg-muted h-1.5 rounded-full relative flex w-full items-center overflow-x-hidden',
        'h-2',  
        props.class,
      )
    "
  >
    <ProgressIndicator
      data-slot="progress-indicator"
      :class="cn(
        'size-full flex-1 transition-all',
        props.indicatorClass
      )"
      :style="`transform: translateX(-${100 - (props.modelValue ?? 0)}%);`"
    />
  </ProgressRoot>
</template>