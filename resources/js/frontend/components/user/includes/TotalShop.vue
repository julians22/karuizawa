<script setup>
    import { forEach } from 'lodash';
    import { computed, defineEmits, onMounted, ref, nextTick, reactive } from 'vue';
    import { useProducts } from '../../../store/product';

    const storeProducts = useProducts();

    const products = computed(function () {
        let productsState = storeProducts.getProducts;
        forEach(productsState, (product, index) => {
            productsState[index].total = product.price * product.qty
        });

        return productsState;
    });

    const coupon = ref(null);

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

    onMounted(() => {
        nextTick(() => {
            storeProducts.setProducts = products.value
        })
    })

    const $emit = defineEmits(['btn-next']);

    const btnProcess = () => {
        $emit('btn-next', 'payment')
    }
</script>

<template>
    <div class="space-y-10">
        <section>
            <div class="flex justify-between items-center bg-primary-50 lg:px-14 lg:py-7 p-6">
                <div class="font-bold text-lg text-white lg:text-xl uppercase tracking-widest">Total shop</div>
                <!-- <form class="w-2/5">
                    <label for="default-search" class="mb-2 font-medium text-gray-900 text-sm dark:text-white sr-only">Search</label>
                    <div class="relative">
                        <input type="search" id="default-search" class="block bg-white px-4 py-2 rounded-full w-full text-gray-900 text-sm pe-10" required />
                        <button type="submit" class="absolute inset-y-0 flex items-center end-0 pe-4">
                            <svg class="text-primary-50 size-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </button>
                    </div>
                </form> -->
            </div>
            <div class="lg:px-14 lg:py-10 p-6">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="py-3 pr-6 text-left text-primary-50 uppercase">Product</th>
                            <th class="px-6 py-3 text-center text-primary-50 uppercase">Price</th>
                            <th class="px-6 py-3 text-center text-primary-50 uppercase">qty</th>
                            <th class="px-6 py-3 text-center text-primary-50 uppercase">Total</th>
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
                                <div class="text-[#606060] text-center lg:text-lg">Rp {{ product.price }}</div>
                            </td>
                            <td class="px-6 py-3 text-center text-primary-50">
                                <div class="flex justify-center w-full text-left number-input" data-controller="quantity">
                                    <button @click="minQty(index)" class="flex items-center border-primary-50 bg-off-white hover:bg-grey-lightest p-2 border border-r-0 font-bold text-grey-darker no-underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="fill-current size-3"><path d="M424 318.2c13.3 0 24-10.7 24-24v-76.4c0-13.3-10.7-24-24-24H24c-13.3 0-24 10.7-24 24v76.4c0 13.3 10.7 24 24 24h400z"/></svg>
                                    </button>
                                    <input type="number" class="border-primary-50 p-2 border w-10 text-center" :value="product.qty" data-target="quantity.value">

                                    <button @click="plusQty(index)" class="flex items-center border-primary-50 bg-off-white hover:bg-grey-lightest p-2 border border-l-0 font-bold text-grey-darker no-underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="fill-current size-3"><path d="M448 294.2v-76.4c0-13.3-10.7-24-24-24H286.2V56c0-13.3-10.7-24-24-24h-76.4c-13.3 0-24 10.7-24 24v137.8H24c-13.3 0-24 10.7-24 24v76.4c0 13.3 10.7 24 24 24h137.8V456c0 13.3 10.7 24 24 24h76.4c13.3 0 24-10.7 24-24V318.2H424c13.3 0 24-10.7 24-24z"/></svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-center text-primary-50">
                                <div class="text-center text-secondary-50 lg:text-lg">Rp {{ product.total ?? product.price }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section>
            <div class="flex justify-between items-center bg-primary-50 lg:px-14 lg:py-7 p-6">
                <div class="font-bold text-lg text-white lg:text-xl uppercase tracking-widest">COUPON CODE & POINTS</div>
            </div>
            <div class="lg:px-14 lg:py-10 p-6">
                <div class="flex items-center gap-4">
                    <div class="text-[#606060] uppercase tracking-widest whitespace-pre">Coupon Code</div>
                    <div class="relative">
                        <span class="top-0 right-0 bottom-0 absolute flex justify-center items-center border-primary-50 border-y bg-secondary pt-2 pr-3 pl-2.5 border-r rounded-r-full w-10 text-primary-50 pointer-events-none">
                            ▼
                        </span>
                        <select v-model="coupon"
                            id="coupon" class="block border-primary-50 bg-white before:bg-blue-400 py-2.5 pr-10 pl-2.5 border focus:border-blue-500 rounded-full focus:ring-blue-500 w-full *:text-[#606060] uppercase">
                            <option value="coupon_1">Coupon #1</option>
                            <option value="coupon_2">Coupon #2</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="right-0 bottom-0 absolute flex">
                <button @click="btnProcess()" class="flex items-center gap-2 bg-secondary-50 p-4 lg:p-6 text-white tracking-widest">
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
