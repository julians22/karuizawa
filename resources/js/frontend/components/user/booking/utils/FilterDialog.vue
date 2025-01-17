<script setup>
    import { ref, defineExpose, computed } from 'vue';

    const dialog = ref(false);

    const emit = defineEmits(['update:dialog']);

    const date = ref('');
    const status = ref('');

    const applyFilter = () => {

        if (applyDisabled.value) {
            alert('Please select at least one filter');
            return;
        }

        emit('update:dialog');
    }

    const setStatus = (value) => {
        if (status.value === value) {
            status.value = '';
        } else {
            status.value = value;
        }
    }

    const applyDisabled = computed(() => {
        return date.value === '' && status.value === '';
    })

    const closeDialog = () => {
        dialog.value = false;
    }

    defineExpose({
        dialog,
        date,
        status
    })
</script>

<template>
    <dialog :open="dialog" style="z-index: 999;">
        <div class="flex justify-center items-center w-full h-full">
            <div class="relative flex-1 md:m-auto max-md:mx-10 rounded-2xl md:max-w-lg lg:max-w-xl xl:max-w-3xl 2xl:max-w-4xl">
                <div class="flex max-md:flex-col bg-white shadow-2xl p-10 rounded-2xl w-full h-full overflow-hidden">
                    <div class="top-3 right-5 absolute">
                        <button @click="closeDialog"><img src="/img/icons/close.png" alt=""></button>
                    </div>
                    <div class="w-full font-roboto">
                        <div class="mb-10 font-bold font-josefin text-2xl text-center text-primary-50 uppercase tracking-wider">filter</div>
                        <div class="space-y-4">
                            <div class="items-center gap-4 grid grid-cols-5">
                                <label for="date" class="block col-span-1 font-bold text-primary-50 xl:text-xl whitespace-nowrap texl-lg">By date</label>
                                <div class="flex col-span-4">
                                    <input type="date" id="date" v-model="date" class="block before:block relative before:right-0 before:-z-10 before:absolute before:inset-y-0 flex-1 before:content-[''] border-primary-50 bg-transparent before:bg-primary-50 p-2.5 border rounded-full w-full before:w-10 text-primary-50 leading-none" required >
                                </div>
                            </div>
                            <div class="items-center gap-4 grid grid-cols-5">
                                <label for="time" class="block col-span-1 font-bold text-primary-50 xl:text-xl whitespace-nowrap texl-lg">Status</label>
                                <div class="flex gap-2 col-span-4">
                                    <button class="btn-filter"
                                        :class="{ active: status === 'waiting' }"
                                        @click="setStatus('waiting')">waiting</button>
                                    <button class="btn-filter" 
                                        :class="{ active: status === 'confirm order' }"
                                        @click="setStatus('confirm order')">confirm order</button>
                                    <button class="btn-filter" 
                                        :class="{ active: status === 'rejected' }"
                                        @click="setStatus('rejected')">rejected</button>
                                </div>
                            </div>
                        </div>
                        <div class="bg-primary-50 my-5 w-full h-[1px]"></div>
                        <div class="flex justify-center gap-4">
                            <button @click="closeDialog" class="relative flex items-center gap-2 bg-primary-50 px-6 py-3 text-white uppercase tracking-widest">
                                <span>cancel</span>
                                <img class="inline-block size-5" src="img/icons/arrw-ck-right.png" alt="">
                            </button>
                            <button @click="applyFilter" 
                                :disabled="applyDisabled"
                                class="btn-apply">
                                <span>apply</span>
                                <img class="inline-block size-5" src="img/icons/arrw-ck-right.png" alt="">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </dialog>
</template>

<style lang="scss" scoped>
    dialog {
        @apply fixed inset-0 w-full h-svh;
        background-color: rgba(0, 0, 0, 0.5);

        @screen md {
            align-content: center;
        }
    }

    .btn-filter{
        @apply bg-primary-200 px-3 pt-3 pb-2 font-josefin text-sm text-white uppercase tracking-wider transition-colors duration-500;
    }

    .btn-filter.active {
        @apply bg-primary-50;
    }

    .btn-apply{
        @apply relative flex items-center gap-2 bg-secondary-50 px-6 py-3 text-white uppercase tracking-widest;
    }

    .btn-apply:disabled {
        @apply bg-gray-200;
    }
</style>
