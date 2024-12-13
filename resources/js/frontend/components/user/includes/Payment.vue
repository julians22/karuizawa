<script setup>
    import { onMounted, ref } from 'vue';


    const props = defineProps({
        selected: Array,
    });

    const totalPayment = ref(0);

    onMounted(() => {

        let total = 0;
        props.selected.forEach(product => {
            // parse float to remove comma
            total += parseFloat(product.price);
        });

        totalPayment.value = total;

    });

</script>

<template>
    <div>
        <section>
            <div class="flex justify-between items-center bg-primary-50 px-14 py-7">
                <div class="font-bold text-2xl text-white uppercase tracking-widest">TOTAL AMOUNT TO BE PAID</div>
            </div>
            <div class="space-y-20 px-14 py-20">
                <div class="items-end gap-20 grid grid-cols-4 w-full">
                    <div>
                        <input class="hidden" type="radio" name="check" :id="`manual-tf`">
                        <label class="flex flex-col items-center space-y-3 px-2 rounded cursor-pointer" :for="`manual-tf`">
                            <div>
                                <img src="img/icons/manual-tf.png" alt="">
                            </div>
                            <div class="font-bold font-roboto text-center text-lg text-secondary-50">Manual Transfer</div>
                            <span class="flex justify-center items-center border-4 border-primary-50 rounded-full text-transparent checkbox-inner size-10"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="check" :id="`credit-card`">
                        <label class="flex flex-col items-center space-y-3 px-2 rounded cursor-pointer" :for="`credit-card`">
                            <div>
                                <img src="img/icons/credit-card.png" alt="">
                            </div>
                            <div class="font-bold font-roboto text-center text-lg text-secondary-50">Credit Card</div>
                            <span class="flex justify-center items-center border-4 border-primary-50 rounded-full text-transparent checkbox-inner size-10"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="check" :id="`edc`">
                        <label class="flex flex-col items-center space-y-3 px-2 rounded cursor-pointer" :for="`edc`">
                            <div>
                                <img src="img/icons/edc.png" alt="">
                            </div>
                            <div class="font-bold font-roboto text-center text-lg text-secondary-50">Electronic <br> Data Capture (EDC)</div>
                            <span class="flex justify-center items-center border-4 border-primary-50 rounded-full text-transparent checkbox-inner size-10"></span>
                        </label>
                    </div>
                    <div class="space-y-3 self-start">
                        <div class="text-[#606060] text-xs">accepted card type</div>
                        <img src="img/icons/visa.png" alt="">
                        <img src="img/icons/mastercard.png" alt="">
                    </div>
                </div>

                <div>
                    <div class="font-bold text-2xl text-primary-50 uppercase tracking-widest">Manual Transfer</div>
                    <div class="flex items-center gap-4 mt-6">
                        <div class="font-roboto text-[#606060] tracking-widest whitespace-pre">Pilih Bank Tujuan</div>
                        <div class="relative">
                            <span class="top-0 right-0 bottom-0 absolute flex justify-center items-center border-primary-50 border-y bg-secondary pt-2 pr-3 pl-2.5 border-r rounded-r-full w-10 text-primary-50 pointer-events-none">
                                ▼
                            </span>
                            <select id="countries" class="block border-primary-50 bg-white before:bg-blue-400 py-2.5 pr-10 pl-2.5 border focus:border-blue-500 rounded-full focus:ring-blue-500 w-full *:text-[#606060] uppercase">
                                <option value="BCA">BCA</option>
                                <option value="BNI">BNI</option>
                            </select>
                        </div>
                        <div class="font-roboto text-[#606060] text-sm">Terms & Conditions</div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="flex justify-between items-center bg-primary-50 px-14 py-7">
                <div class="font-bold text-2xl text-white uppercase tracking-widest">DETAIL ORDER</div>
            </div>

            <div class="space-y-5 px-14 pt-12 pb-32">
                <div class="font-roboto text-[#606060]">
                    <div>Ordered number your shirt </div>
                    <div>BSC0425</div>
                </div>
                <div class="bg-[#606060] opacity-40 w-full h-0.5"></div>
                <div>
                    <div class="grid grid-cols-3 font-bold font-roboto text-secondary-50">
                        <div>Product</div>
                        <div>Qty</div>
                        <div>Price</div>
                    </div>
                    <div class="grid grid-cols-3 mt-2" v-for="product in selected">
                        <div class="font-roboto">
                            <div class="font-bold text-[#606060]">{{ product.product_name }}</div>
                            <div class="text-[#A3A3A3]">{{ product.sku }}</div>
                        </div>
                        <div class="font-bold text-[#606060]">1x</div>
                        <div class="font-bold text-[#606060]">Rp {{ product.price }}</div>
                    </div>
                </div>
                <div class="bg-[#606060] opacity-40 w-full h-0.5"></div>
                <div class="grid grid-cols-3 font-bold font-roboto text-secondary-50">
                    <div class="col-span-2">Subtotal</div>
                    <div>Rp {{ totalPayment }}</div>
                    <div class="col-span-2">Discount (0)</div>
                    <div>Rp 0,-</div>
                    <div class="col-span-2">Total</div>
                    <div>Rp {{ totalPayment }}</div>
                </div>
                <div class="grid grid-cols-3 bg-secondary px-4 pt-4 pb-3 font-bold text-2xl text-primary-50">
                    <div class="col-span-2">TOTAL AMOUNT TO BE PAID</div>
                    <div class="">Rp {{ totalPayment }}</div>
                </div>
                <div class="grid grid-cols-3 mt-1 font-roboto text-[#606060] text-xs">
                    <div class="col-span-2"></div>
                    <div>
                        (Termasuk biaya pajak)
                    </div>
                </div>
            </div>
        </section>

        <div class="right-0 bottom-0 absolute flex">
            <button class="flex items-center gap-2 bg-secondary-50 p-6 text-white tracking-widest">
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
