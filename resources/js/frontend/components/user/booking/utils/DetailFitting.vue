<script setup>
    import { ref, defineExpose } from 'vue';
    import { priceFormat } from "@frontend/helpers/currency";


    const dialog = ref(false);
    const booking = ref(null);;


    const closeDialog = () => {
        dialog.value = false;
        booking.value = null;
    }

    const onPrint = () => {
        console.log(booking.value.product_id);
        window.open(
        `/print-semi-custom/${booking.value.product_id}`,
        '_blank' // <- This is what makes it open in a new window.
        );
        // window.location.href = '/';

    }

    defineExpose({
        dialog,
        booking
    });
</script>

<template>
    <dialog :open="dialog" style="z-index: 999;">
        <div class="flex items-center justify-center w-full h-full overflow-auto">
            <div class="relative flex-1 md:m-auto max-md:mx-10 rounded-2xl md:max-w-lg lg:max-w-xl xl:max-w-3xl 2xl:max-w-4xl">
                <div class="flex w-full h-full p-10 overflow-hidden bg-white shadow-2xl max-md:flex-col rounded-2xl">
                    <div class="absolute top-3 right-5">
                        <button @click="closeDialog"><img src="/img/icons/close.png" alt=""></button>
                    </div>
                    <div class="w-full font-roboto" v-if="booking">
                        <div class="mb-10 text-2xl tracking-wider text-center uppercase font-josefin text-primary-50">detail order</div>

                        <div class="bg-primary-50 my-5 w-full h-[1px]"></div>

                        <div class="text-lg font-bold text-center capitalize">Order number: {{ booking.order.order_number }}</div>

                        <div class="bg-primary-50 my-5 w-full h-[1px]"></div>

                        <div>
                            <div class="text-sm text-center capitalize">total payment</div>
                            <div class="text-xl font-light tracking-widest text-center uppercase font-josefin">{{ priceFormat(booking.price) }}</div>
                        </div>

                        <div class="bg-primary-50 my-5 w-full h-[1px]"></div>

                        <div class="flex justify-center">
                            <table>
                                <thead class="w-full divide-y divide-primary-100">
                                    <tr class="bg-primary-50 text-secondary">
                                        <th class="px-4 py-4 text-left">Product Name</th>
                                        <th class="px-4 py-4 text-left">Qty</th>
                                        <th class="px-4 py-4 text-left">Price</th>
                                        <th class="px-4 py-4 text-left">Total</th>
                                    </tr>
                                </thead>

                                <tbody class="w-full divide-y divide-primary-100">
                                    <tr class="divide-x divide-primary-100" >
                                        <td class="px-4 py-4">{{ booking.product.name }}</td>
                                        <td class="px-4 py-4 text-center">{{ booking.quantity }}</td>
                                        <td class="px-4 py-4">{{ priceFormat(booking.total_price) }}</td>
                                        <td class="px-4 py-4">{{ priceFormat(booking.total_price) }}</td>
                                    </tr>
                                    <tr class="bg-white" v-if="booking.discount > 0">
                                        <td colspan="3" class="px-4 py-4 font-bold text-right text-white bg-primary-50">Coupon</td>
                                        <td class="px-4 py-4 font-bold text-primary-50">
                                            -{{ booking.discount_formatted }} ({{ booking.discount_details.coupon }}%)
                                        </td>
                                    </tr>
                                    <tr class="bg-white">
                                        <td colspan="3" class="px-4 py-4 font-bold text-right text-white bg-primary-50">Subtotal</td>
                                        <td class="px-4 py-4 font-bold text-primary-50">{{ priceFormat(booking.total_price) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="flex items-center justify-end gap-4 mt-10">

                            <!-- button print -->
                            <button @click="onPrint()" class="flex items-center gap-2 p-6 tracking-widest text-white bg-primary-50 font-josefin">
                                <span>PRINT</span>
                                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
                            </button>

                            <!-- button send mail -->
                            <button class="flex items-center gap-2 p-6 tracking-widest text-white bg-secondary-50 font-josefin">
                                <span>SEND MAIL</span>
                                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
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
</style>
