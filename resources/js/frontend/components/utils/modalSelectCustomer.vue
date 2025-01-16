<script setup>
    import { ref, defineExpose, computed} from 'vue';
    import VueSelect from "vue3-select-component";

    const selected = ref("");
    const open = ref(false);

    const form = ref({
        search: ''
    });

    const options = ref([
        { label: 'Option #1', value: 'option_1' }
    ]);

    const selectedOption = computed(() => options.find((option) => option.value === selected.value));

    const onSeach = (search) => {
        console.log('search value:', search);
        let data = [
            { label: 'Option #1', value: 'option_1' },
            { label: 'Option #2', value: 'option_2' },
            { label: 'Option #3', value: 'option_3' },
        ];

        let filtered = data.filter((option) => option.label.toLowerCase().includes(search.toLowerCase()));
        options.value = filtered;
    }

    defineExpose({
        open
    })
</script>

<template>
    <Transition>
        <dialog v-if="open"
            @click.self="open = false"
            class="fixed inset-0 z-[100] flex items-start justify-center w-full h-full bg-black bg-opacity-25 backdrop-blur-sm"
        >
            <div class="bg-white mt-20 p-6 rounded-2xl w-full max-w-2xl">
                <VueSelect
                    v-model="selected"
                    :options="options"
                    @search="onSeach"
                    @option-selected="(option) => console.log(option.label, option.value)"
                    @option-deselected="(option) => console.log(option.label, option.value)"
                    placeholder="Select an option"
                />

                <!-- <div class="relative">
                    <div class="absolute inset-y-0 flex items-center pointer-events-none start-0 ps-3">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input type="search" class="block w-full p-4 text-sm text-gray-900 border rounded-lg border-primary-50 ps-10 bg-gray-50" placeholder="Search Customers..." required
                        v-model="form.search"
                    />
                </div> -->

                <!-- <div></div> -->
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
