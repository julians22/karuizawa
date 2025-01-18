<script setup>
    import { forEach } from 'lodash';
    import { computed, defineEmits, onMounted, ref, nextTick, reactive, watch } from 'vue';
    import { useProducts } from '../../../store/product';
    import { priceFormat } from '../../../helpers/currency';

    const props = defineProps({
        onPage: {
            type: String,
            default: 'rtw'
        }
    })

    const storeProducts = useProducts();

    const products = computed(function () {
        let productsState = storeProducts.getProducts;
        forEach(productsState, (product, index) => {
            productsState[index].total = product.price * product.qty
        });

        return productsState;
    });

    const semiCustom = computed(function () {
        return storeProducts.getSemiCustom;
    })

    const coupon = ref(storeProducts.getCouponRtw);

    const plusQty = (index) => {
        products.value[index].qty += 1;
        products.value[index].total = products.value[index].price * products.value[index].qty
    }

    const minQty = (index) => {
        if (products.value[index].qty <= 1) {
            return;
        }
        // sum total of qty and price
        products.value[index].qty -= 1;
        products.value[index].total = products.value[index].price * products.value[index].qty
    }

    // total price products and semi custom
    const totalAllPrice = computed(function () {
        let totalProduct = 0;
        products.value.forEach(product => {
            // parse float to remove comma
            totalProduct += parseFloat(product.total);
        });

        let sumTotal =  (totalProduct + semiCustom.value.totalPrice);

        let afterDiscount = sumTotal - (sumTotal * coupon.value / 100);

        return afterDiscount;
    });

    onMounted(() => {
        nextTick(() => {
            storeProducts.setProducts = products.value;
        });
    })

    watch(coupon, (value) => {
        storeProducts.coupon_rtw = value;

        console.log(storeProducts.coupon_rtw);

    });

    const $emit = defineEmits(['btn-next']);

    const btnProcess = () => {
        $emit('btn-next', 'payment')
    }

    const btnBack = () => {
        if (props.onPage == 'rtw') {
            $emit('btn-next', 'products');
        }else if (props.onPage == 'semi-custom') {
            $emit('btn-next', 'semi-custom');
        }
        // $emit('btn-next', 'products')
    }
</script>

<template>
    <div class="space-y-10">
        <section>
            <div class="flex items-center justify-between p-6 bg-primary-50 lg:px-14 lg:py-7">
                <div class="text-lg font-bold tracking-widest text-white uppercase lg:text-xl">Total shop</div>
                <!-- <form class="w-2/5">
                    <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                    <div class="relative">
                        <input type="search" id="default-search" class="block w-full px-4 py-2 text-sm text-gray-900 bg-white rounded-full pe-10" required />
                        <button type="submit" class="absolute inset-y-0 flex items-center end-0 pe-4">
                            <svg class="text-primary-50 size-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </button>
                    </div>
                </form> -->
            </div>
            <div class="p-6 lg:px-14 lg:py-10">
                <div v-if="products.length == 0 && semiCustom.length == 0" class="text-xl text-center">Loading...</div>
                <table class="w-full" v-if="products.length !== 0">
                    <thead>
                        <tr>
                            <th class="py-3 pr-6 text-left uppercase text-primary-50">Product</th>
                            <th class="px-6 py-3 text-center uppercase text-primary-50">Price</th>
                            <th class="px-6 py-3 text-center uppercase text-primary-50">qty</th>
                            <th class="px-6 py-3 text-center uppercase text-primary-50">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="products.length == 0">
                            <td colspan="4" class="py-3 text-center text-primary-50">Loading...</td>
                        </tr>
                        <tr class="border-b" v-for="(product, index) in products">
                            <td class="py-3 pr-6 text-left text-primary-50">
                                <div class="text-[#606060]">{{ product.product_name }}</div>
                                <div class="text-[#A3A3A3] text-sm">{{product.sku}}</div>
                            </td>
                            <td class="px-6 py-3 text-center text-primary-50">
                                <div class="text-[#606060] text-center lg:text-lg"
                                    v-html="priceFormat(product.price)"></div>
                            </td>
                            <td class="px-6 py-3 text-center text-primary-50">
                                <div class="flex justify-center w-full text-left number-input" data-controller="quantity">
                                    <button @click="minQty(index)" class="flex items-center p-2 font-bold no-underline border border-r-0 border-primary-50 bg-off-white hover:bg-grey-lightest text-grey-darker">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="fill-current size-3"><path d="M424 318.2c13.3 0 24-10.7 24-24v-76.4c0-13.3-10.7-24-24-24H24c-13.3 0-24 10.7-24 24v76.4c0 13.3 10.7 24 24 24h400z"/></svg>
                                    </button>
                                    <input type="number" class="w-10 p-2 text-center border border-primary-50" :value="product.qty" data-target="quantity.value">

                                    <button @click="plusQty(index)" class="flex items-center p-2 font-bold no-underline border border-l-0 border-primary-50 bg-off-white hover:bg-grey-lightest text-grey-darker">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="fill-current size-3"><path d="M448 294.2v-76.4c0-13.3-10.7-24-24-24H286.2V56c0-13.3-10.7-24-24-24h-76.4c-13.3 0-24 10.7-24 24v137.8H24c-13.3 0-24 10.7-24 24v76.4c0 13.3 10.7 24 24 24h137.8V456c0 13.3 10.7 24 24 24h76.4c13.3 0 24-10.7 24-24V318.2H424c13.3 0 24-10.7 24-24z"/></svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-center text-primary-50">
                                <div class="text-center text-secondary-50 lg:text-lg">{{ priceFormat(product.total ?? product.price) }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-20" v-if="semiCustom.length !== 0">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="py-3 pr-6 text-left uppercase text-primary-50">Semi Custom </th>
                                <th class="px-6 py-3 text-center uppercase text-primary-50">Price</th>
                                <th class="px-6 py-3 text-center uppercase text-primary-50">qty</th>
                                <th class="px-6 py-3 text-center uppercase text-primary-50">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b" >
                                <td class="py-3 pr-6 text-left text-primary-50">
                                    <div class="text-[#606060]">name</div>
                                    <div class="text-[#A3A3A3] text-sm">fabric-code</div>
                                </td>
                                <td class="px-6 py-3 text-center text-primary-50">
                                    <div class="text-[#606060] text-center lg:text-lg"
                                        v-html="priceFormat(semiCustom.totalPrice)"></div>
                                </td>
                                <td class="px-6 py-3 text-center text-primary-50">
                                    1
                                </td>
                                <td class="px-6 py-3 text-center text-primary-50">
                                    <div class="text-center text-secondary-50 lg:text-lg">{{ priceFormat(semiCustom.totalPrice) }}</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between px-4 pt-4 pb-3 text-lg font-bold bg-secondary text-primary-50 lg:text-2xl">
                    <div class="col-span-2">TOTAL AMOUNT</div>
                    <div class="mr-10 text-center">{{ priceFormat(totalAllPrice) }}</div>
                </div>
            </div>
        </section>

        <section>
            <div class="flex items-center justify-between p-6 bg-primary-50 lg:px-14 lg:py-7">
                <div class="text-lg font-bold tracking-widest text-white uppercase lg:text-xl">COUPON CODE & POINTS</div>
            </div>
            <div class="p-6 lg:px-14 lg:py-10">
                <div class="flex items-center gap-4">
                    <div class="text-[#606060] uppercase tracking-widest whitespace-pre">Coupon Code</div>
                    <div class="relative">
                        <span class="top-0 right-0 bottom-0 absolute flex justify-center items-center border-primary-50 border-y bg-secondary pt-2 pr-3 pl-2.5 border-r rounded-r-full w-10 text-primary-50 pointer-events-none">
                            ▼
                        </span>
                        <select v-model="coupon"
                            id="coupon" class="block border-primary-50 bg-white before:bg-blue-400 py-2.5 pr-10 pl-2.5 border focus:border-blue-500 rounded-full focus:ring-blue-500 w-full *:text-[#606060] uppercase">
                            <option :selected="useProducts.getCouponRtw == 0 || coupon == 0" value="0">0%</option>
                            <option :selected="useProducts.getCouponRtw == 10 || coupon == 10" value="10">10%</option>
                            <option :selected="useProducts.getCouponRtw == 20 || coupon == 20" value="20">20%</option>
                            <option :selected="useProducts.getCouponRtw == 30 || coupon == 30" value="30">30%</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="flex justify-between">
                <button @click="btnBack()" class="flex items-center gap-2 p-4 tracking-widest text-white bg-primary-50 lg:p-6">
                    <img class="inline-block rotate-180 mb-1.5" src="img/icons/arrw-ck-right.png" alt="">
                    <span>BACK</span>
                </button>
                <button @click="btnProcess()" class="flex items-center gap-2 p-4 tracking-widest text-white bg-secondary-50 lg:p-6">
                    <span>PROCEED TO PAYMENT</span>
                    <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
                </button>
            </div>
        </section>
    </div>
</template>


<style scoped>
.number-input input[type="number"] {
  appearance: textfield;
}

.number-input input[type="number"]::-webkit-inner-spin-button,
.number-input input[type="number"]::-webkit-outer-spin-button {
  appearance: none;
}
</style>
