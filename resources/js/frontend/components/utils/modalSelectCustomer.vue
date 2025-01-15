<script setup>
    import { ref, defineExpose, computed} from 'vue';
    import VueSelect from "vue3-select-component";

    const selected = ref("");
    const open = ref(false);

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
            class="fixed inset-0 z-10 flex items-start justify-center w-full h-full bg-black bg-opacity-25 backdrop-blur-sm"
        >
            <div class="w-full max-w-2xl p-6 mt-20 bg-white rounded-2xl">
                <VueSelect
                    v-model="selected"
                    :options="options"
                    @search="onSeach"
                    @option-selected="(option) => console.log(option.label, option.value)"
                    @option-deselected="(option) => console.log(option.label, option.value)"
                    placeholder="Select an option"
                />
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
