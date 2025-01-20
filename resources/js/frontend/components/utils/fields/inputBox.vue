<script setup>
import {
  PinInput,
  PinInputGroup,
  PinInputInput,
} from '@/components/ui/pin-input'
import { ref, defineProps, watch, reactive } from 'vue'

const props = defineProps({
    digitCount: {
        type: Number,
        default: 4
    },
    inputType: {
        type: String,
        default: 'text'
    }
});

const emit = defineEmits(['update:input']);

const valueInput = ref([])

const handleComplete = (e) => {
    //
}
const onUpdate = (e) => {
    emit('update:input', e.join(''));
}

defineExpose({
    valueInput
})

</script>

<template>
  <div>
    <PinInput
        id="pin-input"
        v-model="valueInput"
        placeholder=""
        class="font-roboto"
        :type="inputType"
        @complete="handleComplete"
        @update:modelValue="onUpdate"
    >
      <PinInputGroup>
        <PinInputInput
        class="box-input border-primary-50 font-roboto"
          v-for="(id, index) in props.digitCount"
          :key="id"
          :index="index"
        />
      </PinInputGroup>
    </PinInput>
  </div>
</template>

<style scoped>
    input[type="number"] {
        appearance: textfield;
    }

    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        appearance: none;
    }
</style>
