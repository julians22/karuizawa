<script setup>
    import moment from 'moment';
    import { ref, defineProps, defineEmits } from 'vue';

    const props = defineProps({
        api_set_status: String
    });

    const emit = defineEmits(['get-bookings']);

    const dialog = ref(false);
    const product = ref(null);

    const setStatusFinish = () => {
        let semi_custom_id = product.value.id;
        let semi_custom_type = product.value.order_item.type;
        let url = `${props.api_set_status}`;

        axios.post(url, {
            status: 'finish',
            id: semi_custom_id,
            type: semi_custom_type,
            finish_at: moment().format()
        }).then((response) => {
            emit('get-bookings');
            closeDialog();
        }).catch((error) => {
            console.log(error);
        });
    }

    const closeDialog = () => {
            dialog.value = false;
    }

    defineExpose({
        dialog,
        product
    })
</script>

<template>


    <dialog :open="dialog" style="z-index: 999;">

        <div class="flex justify-center items-center w-full h-full overflow-auto">
            <div class="relative flex-1 md:m-auto max-md:mx-10 rounded-2xl md:max-w-lg lg:max-w-xl xl:max-w-3xl 2xl:max-w-4xl">
                <div class="flex max-md:flex-col bg-white shadow-2xl p-10 rounded-2xl w-full h-full">
                    <div class="top-3 right-5 absolute">
                        <button @click="closeDialog"><img src="/img/icons/close.png" alt=""></button>
                    </div>
                    <div class="w-full font-roboto text-center">
                        <div class="mb-5 text-center" style="font-size: 1.5rem; font-weight: 600; text-transform: uppercase; font-family: 'Josefin Sans', sans-serif; color: #FFA500;">Confirm Staus</div>

                        <div>Are you sure to finish this order</div>

                        <small class="text-red-500">*You cannot modify this changes on the next step</small>


                        <div class="flex justify-center gap-4 mt-5">
                            <button class="bg-primary-50 px-4 py-2 rounded text-white" @click="setStatusFinish">FINISH</button>
                        <button class="bg-secondary px-4 py-2 rounded" @click="closeDialog">CANCEL</button>
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
