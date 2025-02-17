<script setup>
    import { computed, defineAsyncComponent, onMounted, ref, defineEmits } from 'vue';
    import { useProducts } from '../../../store/product';
    import { useCustomer } from '../../../store/customer';
    import { priceFormat } from '../../../helpers/currency';

    const props = defineProps({
        csrf: String,
        user: Object,
        api_store_order: String,
    });

    const sendingPayment = ref(false);
    const doPayment = defineAsyncComponent(() => import('../../utils/paymentModal.vue'));

    const childDoPayment = ref(null);

    const products = computed(() => useProducts().getProducts);
    const customer = computed(() => useCustomer().getCustomer);
    const couponUsed = computed(() => useProducts().coupon_rtw);
    const semiCustom = computed(() => useProducts().getSemiCustom);

    const totalPayment = ref(0);

    const afterDiscount = computed(() => {
        return totalPayment.value - (totalPayment.value * (couponUsed.value / 100));
    });

    const discount = computed(() => {
        return totalPayment.value * (couponUsed.value / 100);
    });

    const selectedPayment = ref('manual-tf');
    const preferredBank = ref('BCA');

    const showPreferredBank = computed(() => {
        return selectedPayment.value === 'manual-tf';
    });

    const confirmPayment = async () => {
        childDoPayment.value.open = true;
        sendingPayment.value = true;
        const doSend = await sendOrder();

        console.log(doSend);

        if (doSend) {
            window.location.href = '/customer-booking';
        }

    }

    const sendOrder = async () => {
        axios.post(props.api_store_order, {
            products: products.value,
            payment: selectedPayment.value,
            bank: preferredBank.value,
            customer_id: customer.value ? customer.value.id : null,
            coupon: couponUsed.value,
        })
        .then(response => {
            if (response.data.success) {
                sendingPayment.value = false;
                childDoPayment.value.open = true;
                childDoPayment.value.status = 'success';
                childDoPayment.value.message = 'Payment Success';
            }

            return true;
        })
        .catch(error => {
            sendingPayment.value = false;
            childDoPayment.value.open = true;
            childDoPayment.value.status = 'error';
            childDoPayment.value.message = 'Payment Failed error: ' + error.response.data.message;
        });

        return false;
    }


    onMounted(() => {

        let total = 0;
        products.value.forEach(product => {
            // parse float to remove comma
            total += parseFloat(product.total);
        });

        totalPayment.value = total;
    });

    const $emit = defineEmits(['btn-next']);

    const btnBack = () => {
        $emit('btn-next', 'total-shop')
    }

</script>

<template>
    <div>
        <doPayment
            :sending-payment="sendingPayment"
            ref="childDoPayment"/>
        <section>
            <div class="flex justify-between items-center bg-primary-50 p-6 lg:px-14 lg:py-7">
                <div class="font-bold text-white text-lg lg:text-xl uppercase tracking-widest">TOTAL AMOUNT TO BE PAID</div>
            </div>
            <div class="space-y-20 p-6 lg:px-14 lg:py-20">
                <div class="items-end gap-20 grid grid-cols-4 w-full">
                    <div>
                        <input
                            v-model="selectedPayment"
                            class="hidden"
                            type="radio"
                            name="check"
                            value="manual-tf"
                            :id="`manual-tf`">
                        <label class="flex flex-col items-center space-y-3 px-2 rounded cursor-pointer" :for="`manual-tf`">
                            <div>
                                <img src="img/icons/manual-tf.png" alt="">
                            </div>
                            <div class="font-roboto font-bold text-secondary-50 text-sm lg:text-base xl:text-lg text-center text-nowrap">Manual Transfer</div>
                            <span class="flex justify-center items-center border-4 border-primary-50 rounded-full size-10 text-transparent checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input
                            v-model="selectedPayment"
                            class="hidden"
                            type="radio"
                            name="check"
                            value="credit-card"
                            :id="`credit-card`">
                        <label class="flex flex-col items-center space-y-3 px-2 rounded cursor-pointer" :for="`credit-card`">
                            <div>
                                <img src="img/icons/credit-card.png" alt="">
                            </div>
                            <div class="font-roboto font-bold text-secondary-50 text-sm lg:text-base xl:text-lg text-center text-nowrap">Credit Card</div>
                            <span class="flex justify-center items-center border-4 border-primary-50 rounded-full size-10 text-transparent checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input
                            v-model="selectedPayment"
                            class="hidden"
                            type="radio"
                            name="check"
                            value="debit-card"
                            :id="`debit-card`">
                        <label class="flex flex-col items-center space-y-3 px-2 rounded cursor-pointer" :for="`debit-card`">
                            <div>
                                <img src="img/icons/credit-card.png" alt="">
                            </div>
                            <div class="font-roboto font-bold text-secondary-50 text-sm lg:text-base xl:text-lg text-center text-nowrap">Debit Card</div>
                            <span class="flex justify-center items-center border-4 border-primary-50 rounded-full size-10 text-transparent checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input
                            v-model="selectedPayment"
                            class="hidden"
                            type="radio"
                            name="check"
                            value="qris"
                            :id="`qris`">
                        <label class="flex flex-col items-center space-y-3 px-2 rounded cursor-pointer" :for="`qris`">
                            <div class="font-roboto font-bold text-secondary-50 text-sm lg:text-base xl:text-lg text-center text-nowrap">QRIS</div>
                            <span class="flex justify-center items-center border-4 border-primary-50 rounded-full size-10 text-transparent checkbox-inner"></span>
                        </label>
                    </div>
                </div>

                <div v-show="showPreferredBank">
                    <div class="font-bold text-primary-50 text-lg lg:text-2xl uppercase tracking-widest">Manual Transfer</div>
                    <div class="flex items-center gap-4 mt-6">
                        <div class="font-roboto text-[#606060] tracking-widest whitespace-pre">Pilih Bank Tujuan</div>
                        <div class="relative">
                            <span class="top-0 right-0 bottom-0 absolute flex justify-center items-center bg-secondary pt-2 pr-3 pl-2.5 border-primary-50 border-y border-r rounded-r-full w-10 text-primary-50 pointer-events-none">
                                ▼
                            </span>
                            <select
                                v-model="preferredBank"
                                id="prefered-bank" class="block bg-white before:bg-blue-400 py-2.5 pr-10 pl-2.5 border border-primary-50 focus:border-blue-500 rounded-full focus:ring-blue-500 w-full *:text-[#606060] uppercase">
                                <option value="BCA">BCA</option>
                            </select>
                        </div>
                        <div class="font-roboto text-[#606060] text-sm">Terms & Conditions</div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="flex justify-between items-center bg-primary-50 p-6 lg:px-14 lg:py-7">
                <div class="font-bold text-white text-lg lg:text-xl uppercase tracking-widest">DETAIL ORDER</div>
            </div>

            <div class="space-y-5 px-14 pt-12 pb-32">
                <div class="font-roboto text-[#606060]">
                    <div>Ordered number your shirt </div>
                    <div>BSC0425</div>
                </div>
                <div class="bg-[#606060] opacity-40 w-full h-0.5"></div>
                <div>
                    <div class="grid grid-cols-3 font-roboto font-bold text-secondary-50">
                        <div>Product</div>
                        <div>Qty</div>
                        <div>Price</div>
                    </div>
                    <div class="grid grid-cols-3 mt-2" v-for="product in products">
                        <div class="font-roboto">
                            <div class="font-bold text-[#606060]">{{ product.product_name }}</div>
                            <div class="text-[#A3A3A3]">{{ product.sku }}</div>
                        </div>
                        <div class="font-bold text-[#606060]">{{ product.qty }}</div>
                        <div class="font-bold text-[#606060]"
                            v-html="priceFormat(product.price)"></div>
                    </div>
                </div>
                <div>
                    <div class="grid grid-cols-3 font-roboto font-bold text-secondary-50">
                        <div>Semi Custom</div>
                        <div>Qty</div>
                        <div>Price</div>
                    </div>
                    <div class="grid grid-cols-3 mt-2">
                        <div class="font-roboto">
                            <div class="font-bold text-[#606060]">name</div>
                            <div class="text-[#A3A3A3]">fabric-code</div>
                        </div>
                        <div class="font-bold text-[#606060]">1</div>
                        <div class="font-bold text-[#606060]"
                            v-html="priceFormat(semiCustom.totalPrice)"></div>
                    </div>
                </div>
                <div class="bg-[#606060] opacity-40 w-full h-0.5"></div>
                <div class="grid grid-cols-3 font-roboto font-bold text-secondary-50">
                    <div class="col-span-2">Subtotal</div>
                    <div
                        v-html="priceFormat(totalPayment)"></div>
                    <div class="col-span-2">Discount ({{ couponUsed }}%)</div>
                    <div v-html="priceFormat(discount)"></div>
                    <div class="col-span-2">Total</div>
                    <div
                        v-html="priceFormat(afterDiscount)"
                        ></div>
                </div>
                <div class="grid grid-cols-3 bg-secondary px-4 pt-4 pb-3 font-bold text-primary-50 text-lg lg:text-2xl">
                    <div class="col-span-2">TOTAL AMOUNT TO BE PAID</div>
                    <div class="">{{ priceFormat(afterDiscount) }}</div>
                </div>
                <div class="grid grid-cols-3 mt-1 font-roboto text-[#606060] text-xs">
                    <div class="col-span-2"></div>
                    <div>
                        (Termasuk biaya pajak)
                    </div>
                </div>
            </div>
        </section>

        <div class="flex justify-between">
            <button @click="btnBack()" class="flex items-center gap-2 bg-primary-50 p-4 lg:p-6 text-white tracking-widest">
                    <img class="inline-block mb-1.5 rotate-180" src="img/icons/arrw-ck-right.png" alt="">
                    <span>BACK</span>
            </button>
            <button @click="confirmPayment()"
                class="flex items-center gap-2 bg-secondary-50 p-4 lg:p-6 text-white tracking-widest">
                <span>PROCEED TO PAYMENT</span>
                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
            </button>
        </div>
    </div>
</template>

<style scoped>
    input[type="radio"] + label span.checkbox-inner {
        @apply relative border-primary-50;
    }
    input[type="radio"]:checked + label span.checkbox-inner::before {
        content: '';
        @apply bg-primary-50 z-10 rounded-full block size-4/5 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2;

    }
    .checkbox-inner {
        @apply inline-block border-2 border-primary-50 rounded-full size-6;
        background: transparent no-repeat center;
    }
</style>
