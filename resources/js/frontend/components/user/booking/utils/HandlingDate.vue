<script setup>

import { ref, defineExpose } from 'vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';


const props = defineProps({
    api_set_handling: String,
    user: Object
});


// define emit
const emit = defineEmits(['handling-date-updated']);

const date = ref(new Date());

const format = (date) => {
    const day = date.getDate();
    const month = date.getMonth() + 1;
    const year = date.getFullYear();

    return `${day}/${month}/${year}`;
}

const item = ref(null);


const dialog = ref(false);
const order_item = ref(null);

const setFittingDate = () => {
    let semi_custom_id = order_item.value.product.id;
    let url = `${props.api_set_handling}/${semi_custom_id}`;

    axios.post(url, {
        date: format(date.value)
    }).then((response) => {
        console.log(response.data);

        item.value = response.data;
        closeDialog();
    }).catch((error) => {
        console.log(error);
    });
}

const closeDialog = () => {
    dialog.value = false;

    emit('handling-date-updated', item.value);
}

defineExpose({
    dialog,
    order_item
});

</script>

<template>


<dialog :open="dialog" style="z-index: 999;">

    <div class="flex items-center justify-center w-full h-full overflow-auto">
        <div class="relative flex-1 md:m-auto max-md:mx-10 rounded-2xl md:max-w-lg lg:max-w-xl xl:max-w-3xl 2xl:max-w-4xl">
            <div class="flex w-full h-full p-10 bg-white shadow-2xl max-md:flex-col rounded-2xl">
                <div class="absolute top-3 right-5">
                    <button @click="closeDialog"><img src="/img/icons/close.png" alt=""></button>
                </div>
                <div class="w-full font-roboto" v-if="order_item">
                    <div class="mb-10 text" style="font-size: 1.5rem; font-weight: 600; text-transform: uppercase; font-family: 'Josefin Sans', sans-serif; color: #FFA500;">Set Fitting Date</div>
                    <p class="text-center">
                        <!-- add wording for alerting user they cannot modify thie changes -->
                        <span class="text-red-500">*You cannot modify this changes on the next step</span>
                    </p>
                    
                    <VueDatePicker v-model="date" :format="format" :min-date="new Date()" ignore-time-validation :enableTimePicker="false" />

                    <div class="flex justify-center mt-5">
                        <button @click="setFittingDate" class="px-4 py-2 tracking-widest text-white bg-primary-50 lg:px-3 lg:py-2 font-josefin">Set Fitting Date</button>
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
</style>
