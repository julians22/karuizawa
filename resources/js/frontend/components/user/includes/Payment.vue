<template>
    <div>
        <section>
            <div class="flex items-center justify-between py-7 px-14 bg-primary-50">
                <div class="text-2xl font-bold tracking-widest text-white uppercase">TOTAL AMOUNT TO BE PAID</div>
            </div>
            <div class="py-20 space-y-20 px-14">
                <div class="grid items-end w-full grid-cols-4 gap-20">
                    <div>
                        <input class="hidden" type="radio" name="check" :id="`manual-tf`">
                        <label class="flex flex-col items-center px-2 space-y-3 rounded cursor-pointer" :for="`manual-tf`">
                            <div>
                                <img src="img/icons/manual-tf.png" alt="">
                            </div>
                            <div class="text-lg font-bold text-center text-secondary-50 font-roboto">Manual Transfer</div>
                            <span class="flex items-center justify-center text-transparent border-4 rounded-full size-10 border-primary-50 checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="check" :id="`credit-card`">
                        <label class="flex flex-col items-center px-2 space-y-3 rounded cursor-pointer" :for="`credit-card`">
                            <div>
                                <img src="img/icons/credit-card.png" alt="">
                            </div>
                            <div class="text-lg font-bold text-center text-secondary-50 font-roboto">Credit Card</div>
                            <span class="flex items-center justify-center text-transparent border-4 rounded-full size-10 border-primary-50 checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="check" :id="`edc`">
                        <label class="flex flex-col items-center px-2 space-y-3 rounded cursor-pointer" :for="`edc`">
                            <div>
                                <img src="img/icons/edc.png" alt="">
                            </div>
                            <div class="text-lg font-bold text-center text-secondary-50 font-roboto">Electronic <br> Data Capture (EDC)</div>
                            <span class="flex items-center justify-center text-transparent border-4 rounded-full size-10 border-primary-50 checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="self-start space-y-3">
                        <div class="text-xs text-[#606060]">accepted card type</div>
                        <img src="img/icons/visa.png" alt="">
                        <img src="img/icons/mastercard.png" alt="">
                    </div>
                </div>

                <div>
                    <div class="text-2xl font-bold tracking-widest uppercase text-primary-50">Manual Transfer</div>
                    <div class="flex items-center gap-4 mt-6">
                        <div class="tracking-widest text-[#606060] font-roboto whitespace-pre">Pilih Bank Tujuan</div>
                        <div class="relative">
                            <span class="absolute top-0 bottom-0 border-primary-50 border-y border-r right-0 flex items-center justify-center w-10 pt-2 pl-2.5 pr-3 rounded-r-full pointer-events-none bg-secondary text-primary-50">
                                ▼
                            </span>
                            <select id="countries" class="bg-white *:text-[#606060] uppercase rounded-full border border-primary-50 focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5 pl-2.5 pr-10  before:bg-blue-400">
                                <option value="BCA">BCA</option>
                                <option value="BNI">BNI</option>
                            </select>
                        </div>
                        <div class="text-[#606060] font-roboto text-sm">Terms & Conditions</div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="flex items-center justify-between py-7 px-14 bg-primary-50">
                <div class="text-2xl font-bold tracking-widest text-white uppercase">DETAIL ORDER</div>
            </div>

            <div class="pt-12 pb-32 space-y-5 px-14">
                <div class="font-roboto text-[#606060]">
                    <div>Ordered number your shirt </div>
                    <div>BSC0425</div>
                </div>
                <div class="h-0.5 w-full bg-[#606060] opacity-40"></div>
                <div>
                    <div class="grid grid-cols-3 font-bold font-roboto text-secondary-50">
                        <div>Product</div>
                        <div>Qty</div>
                        <div>Price</div>
                    </div>
                    <div class="grid grid-cols-3 mt-2">
                        <div class="font-roboto">
                            <div class="text-[#606060] font-bold">Kemeja Karuizawa Shirt Limited Luxury Lengan Pendek</div>
                            <div class="text-[#A3A3A3]">KXS 004 Ukuran L</div>
                        </div>
                        <div class="text-[#606060] font-bold">1x</div>
                        <div class="text-[#606060] font-bold">Rp 898.000,-</div>
                    </div>
                </div>
                <div class="h-0.5 w-full bg-[#606060] opacity-40"></div>
                <div class="grid grid-cols-3 font-bold font-roboto text-secondary-50">
                    <div class="col-span-2">Subtotal</div>
                    <div>Rp 898.000,-</div>
                    <div class="col-span-2">Discount (0)</div>
                    <div>Rp 0,-</div>
                    <div class="col-span-2">Total</div>
                    <div>Rp 898.000,-</div>
                </div>
                <div class="grid grid-cols-3 px-4 pt-4 pb-3 text-2xl font-bold text-primary-50 bg-secondary">
                    <div class="col-span-2">TOTAL AMOUNT TO BE PAID</div>
                    <div class="">Rp 898.000,-</div>
                </div>
                <div class="grid grid-cols-3 font-roboto text-[#606060] text-xs mt-1">
                    <div class="col-span-2"></div>
                    <div>
                        (Termasuk biaya pajak)
                    </div>
                </div>
            </div>
        </section>

        <div class="absolute bottom-0 right-0 flex">
            <button class="flex items-center gap-2 p-6 tracking-widest text-white bg-secondary-50">
                <span>PROCEED TO PAYMENT</span>
                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
            </button>
        </div>
    </div>
</template>

<style scoped>
    input[type="radio"] + label span.checkbox-inner {
        @apply border-primary-50 relative;
    }
    input[type="radio"]:checked + label span.checkbox-inner::before {
        content: '';
        @apply bg-primary-50 z-10 rounded-full block size-4/5 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2;

    }
    .checkbox-inner {
        @apply inline-block size-6 rounded-full border-2 border-primary-50;
        background: transparent no-repeat center;
    }
</style>
