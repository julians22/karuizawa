<script setup>
import { ref, reactive, defineExpose } from 'vue';

const props = defineProps({
    default: String,

    digitCount: {
      type: Number,
      required: true
    },

    inputType: {
      type: String,
      default: 'text'
    }
});

const digits = reactive([])

if (props.default && props.default.length === props.digitCount) {
    for (let i = 0; i < props.digitCount; i++) {
        digits[i] = props.default.charAt(i)
    }
} else {
    for (let i = 0; i < props.digitCount; i++) {
        digits[i] = null;
    }
}

const inputCont = ref(null);

const emit = defineEmits(['update:input']);

const isDigitsFull = function () {
  for (const elem of digits) {
    if (elem == null || elem == undefined) {
      return false;
    }
  }
  return true;
}

const handleKeyDown = function (event, index) {
    if (event.key !== "Tab" &&
        event.key !== "ArrowRight" &&
        event.key !== "ArrowLeft"
    ) {
      event.preventDefault();
    }

    if (event.key === "Backspace") {
      digits[index] = null;

      if (index != 0) {
        (inputCont.value.children)[index-1].focus();
      }

      emit('update:input', digits.join(''))
      return;
    }

    if ((new RegExp('^([0-9a-zA-Z])$')).test(event.key)) {
        digits[index] = event.key.toUpperCase();

        if (index != props.digitCount - 1) {
          (inputCont.value.children)[index+1].focus();
        }

        // if (isDigitsFull()) {
            emit('update:input', digits.join(''))
        // }
    }

}

defineExpose({
    digits
})

</script>

<template>
    <div ref="inputCont" class="box-input-wrapper">
        <input
            v-for="(digit, index) in digits"
            v-model="digits[index]"
            :key="digit+index"
            :autofocus="index === 0"
            :type="inputType"
            maxlength="1"
            class="box-input"
            @keydown="handleKeyDown($event, index)"
            >
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
