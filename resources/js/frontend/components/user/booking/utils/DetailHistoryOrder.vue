<script setup>
    import { ref, defineExpose } from 'vue';
    import { priceFormat } from '../../../../helpers/currency.js';

    const dialog = ref(false);
    const booking = ref(null);;


    const closeDialog = () => {
        dialog.value = false;
        booking.value = null;
    }

    const onPrint = () => {
        console.log(booking);
        window.open(
        `/print-bill/${booking.value.order_id}`,
        '_blank'
        );
    }

    const afterDiscount = (price, discount=0) => {

        console.log(price, discount);

        // parseInt
        // calculate discount
        let totalAmount = parseInt(price) - parseInt(discount);

        return priceFormat(totalAmount);
    }

    defineExpose({
        dialog,
        booking
    });
</script>

<template>
    <dialog :open="dialog" style="z-index: 999;">
        <div class="flex justify-center items-center w-full h-full overflow-auto">
            <div class="relative flex-1 md:m-auto max-md:mx-10 rounded-2xl md:max-w-lg lg:max-w-xl xl:max-w-3xl 2xl:max-w-4xl">
                <div class="flex max-md:flex-col bg-white shadow-2xl p-10 rounded-2xl w-full h-full overflow-hidden">
                    <div class="top-3 right-5 absolute">
                        <button @click="closeDialog"><img src="/img/icons/close.png" alt=""></button>
                    </div>
                    <div class="w-full font-roboto" v-if="booking">
                        <div class="mb-10 font-josefin text-primary-50 text-2xl text-center uppercase tracking-wider">detail order</div>

                        <div class="bg-primary-50 my-5 w-full h-[1px]"></div>

                        <div class="font-bold text-lg text-center capitalize">Order number: {{ booking.booking_code }}</div>

                        <div class="bg-primary-50 my-5 w-full h-[1px]"></div>

                        <div>
                            <div class="text-sm text-center capitalize">total payment</div>
                            <div class="font-josefin font-light text-xl text-center uppercase tracking-widest">{{ booking.amount_formatted }}</div>
                        </div>

                        <div class="bg-primary-50 my-5 w-full h-[1px]"></div>

                        <div class="flex justify-center h-[35vh] overflow-y-auto">
                            <table>
                                <thead class="divide-y divide-primary-100 w-full">
                                    <tr class="bg-primary-50 text-secondary">
                                        <th class="px-4 py-4 text-left">Product Name</th>
                                        <th class="px-4 py-4 text-left">Qty</th>
                                        <th class="px-4 py-4 text-left">Price</th>
                                        <th class="px-4 py-4 text-left">Total</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-primary-100 w-full">
                                    <tr class="divide-x divide-primary-100" v-for="item in booking.items">
                                        <td class="px-4 py-4">{{ item.product_name }}</td>
                                        <td class="px-4 py-4 text-center">{{ item.qty }}</td>
                                        <td class="px-4 py-4">{{ item.price_formatted }}</td>
                                        <td class="px-4 py-4">
                                            {{ afterDiscount(item.price, item.discount) }} <span v-if="item.discount_detail?.discount">(-{{ item.discount_detail.discount }}%)</span>
                                        </td>
                                    </tr>
                                    <tr class="bg-white" v-if="booking.discount > 0">
                                        <td colspan="3" class="bg-primary-50 px-4 py-4 font-bold text-white text-right">Coupon</td>
                                        <td class="px-4 py-4 font-bold text-primary-50">
                                            -{{ booking.discount_formatted }} ({{ booking.discount_details.coupon }}%)
                                        </td>
                                    </tr>
                                    <tr class="bg-white">
                                        <td colspan="3" class="bg-primary-50 px-4 py-4 font-bold text-white text-right">Subtotal</td>
                                        <td class="px-4 py-4 font-bold text-primary-50">{{ booking.amount_formatted }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-end items-center gap-4 mt-10">

                            <!-- button print -->
                            <button @click="onPrint()" class="flex items-center gap-2 bg-primary-50 p-6 font-josefin text-white tracking-widest">
                                <span>PRINT</span>
                                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
                            </button>

                            <!-- button send mail -->
                            <!-- <button class="flex items-center gap-2 bg-secondary-50 p-6 font-josefin text-white tracking-widest">
                                <span>SEND MAIL</span>
                                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
                            </button> -->
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
