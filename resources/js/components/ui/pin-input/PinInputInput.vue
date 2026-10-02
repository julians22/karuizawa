<script setup>
import { cn } from '@/lib/utils';
import { PinInputInput, useForwardProps } from 'radix-vue';
import { computed } from 'vue';

const props = defineProps({
  index: { type: Number, required: true },
  disabled: { type: Boolean, required: false },
  asChild: { type: Boolean, required: false },
  as: { type: null, required: false },
  class: { type: null, required: false },
});

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props;
  return delegated;
});

const forwardedProps = useForwardProps(delegatedProps);
</script>

<template>
  <PinInputInput
    v-bind="forwardedProps"
    :class="
      cn(
        'focus:z-10 relative focus:relative flex justify-center items-center border-neutral-200 border-y dark:border-neutral-800 border-r first:border-l focus:outline-none focus:ring-1 focus:ring-neutral-950 dark:focus:ring-neutral-300 w-8 h-8 font-bold text-lg text-center transition-all',
        props.class,
      )
    "
  />
</template>
