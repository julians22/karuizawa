<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    sendingPayment: {
        type: Boolean,
        default: false
    }
});

const open = ref(false);
const status = ref('');
const message = ref('');

defineExpose({
    open,
    status,
    message
})

</script>

<template>
    <Transition>
        <dialog v-if="open"
            class="z-10 fixed inset-0 flex justify-center items-start bg-black bg-opacity-25 backdrop-blur-sm w-full h-full"
        >
            <div class="bg-white mt-20 p-6 rounded-2xl w-full max-w-2xl h-80">

                <div class="flex justify-center items-center w-full h-full">
                    <div v-if="sendingPayment" class="text-lg">Processing Payment...</div>
                    <div v-else-if="status === 'success'" class="text-primary-50 text-xl">
                        {{ message }}

                        <div class="flex justify-center mt-5">
                            <a href="/ready-to-wear" class="bg-primary-50 px-6 py-3 rounded-full text-white">Back to Rtw</a>
                        </div>

                    </div>
                    <div v-else class="text-lg text-red-500">
                        {{ message }}
                        <div class="flex justify-center mt-5">
                            <a href="/ready-to-wear" class="bg-primary-50 px-6 py-3 rounded-full text-white">Back to Rtw</a>
                        </div>

                    </div>
                </div>
            </div>
        </dialog>
    </Transition>
</template>

<style scoped>
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}

:deep(.vue-select .menu) {
    @apply transition-all duration-300 ease-in-out;
    position: relative;
}
</style>
