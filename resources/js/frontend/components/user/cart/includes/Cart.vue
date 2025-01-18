<script setup>
    import { forEach } from 'lodash';
    import { computed, defineEmits, onMounted, ref, nextTick, reactive, watch } from 'vue';
    import { useProducts } from "@frontend/store/product";
    import { priceFormat } from "@frontend/helpers/currency";
    import { useCustomer } from "@frontend/store/customer";
    import { useOrder } from "@frontend/store/order";
    import { usePage } from "@frontend/store/page";

    const props = defineProps({
        onPage: {
            type: String,
            default: 'products'
        },
        user: Object,
        api_store_order: String,
        coupons: Array
    });

    const storePage = usePage();

    const customer = computed(() => useCustomer().getCustomer);
    const orderDate = ref(useOrder().getDateAndTime);

    const ordersData = computed(() => {
        return {
            products: products.value,
            customer: customer.value ? customer.value.id : null,
            coupon: coupon.value ? coupon.value : 0,
            semi_custom: semiCustom.value
        }
    });

    const sendOrder = async () => {

        let semi_custom_data = {
            basic_form: {},
            base_price: 0,
            base_discount: 0,
            base_note: '',
            option_form: {},
            option_total: 0,
            option_additional_price: 0,
            option_discount: 0,
            option_note: '',
            size: {},
            total: 0
        };

        let product_data = [];

        if (ordersData.value.products.length !== 0) {
            forEach(ordersData.value.products, (product, index) => {
                product_data.push({
                    sku: product.sku,
                    id: product.id,
                    qty: product.qty,
                    price: product.price,
                    total: product.total
                });
            });
        }

        if (ordersData.value.semi_custom.length !== 0) {
            forEach(ordersData.value.semi_custom, (semi_custom, index) => {

                if (index == 'totalPrice') {
                    semi_custom_data.total = semi_custom;
                }

                if (index == 'basic'){
                    semi_custom_data.basic_form = semi_custom.form;

                    if (semi_custom.amount) {
                        if (semi_custom.amount.price) {
                            semi_custom_data.base_price = semi_custom.amount.price;
                        }
                        if (semi_custom.amount.discount) {
                            semi_custom_data.base_discount = semi_custom.amount.discount;
                        }
                    }

                    if (semi_custom.formSize) {
                        semi_custom_data.size = semi_custom.formSize;
                    }

                    if (semi_custom.additionalNote) {
                        semi_custom_data.base_note = semi_custom.additionalNote;
                    }
                }

                if (index == 'option') {
                    semi_custom_data.option_form = semi_custom.form;

                    if (semi_custom.amount) {
                        if (semi_custom.amount.price) {
                            semi_custom_data.option_additional_price = semi_custom.amount.price;
                        }
                        if (semi_custom.amount.discount) {
                            semi_custom_data.option_discount = semi_custom.amount.discount;
                        }
                        if (semi_custom.amount.optionTotal) {
                            semi_custom_data.option_total = semi_custom.amount.optionTotal;
                        }
                    }

                    if (semi_custom.additionalNote) {
                        semi_custom_data.option_note = semi_custom.additionalNote;
                    }
                }

            });
        }

        let orders = {
            customer: ordersData.value.customer,
            products: product_data,
            semi_custom: semi_custom_data,
            coupon: ordersData.value.coupon,
            user: props.user.id,
            store_id: props.user.store_id ?? 0,
            order_date: orderDate.value
        }


        axios.post(props.api_store_order, orders)
        .then(response => {
            if (response.data.success) {
                console.log(response.data);
                // $emit('btn-next', 'payment');
                // redirect to payment page

                storeProducts.setSlug = [];
                storeProducts.setProducts = [];
                storeProducts.semi_custom = [];
                storeProducts.coupon_rtw = 0;
                storeProducts.semi_custom = [];

                useCustomer().resetCustomer();

                window.location.href = response.data.redirect;
            }

            console.log(response.data);
        })
        .catch(error => {
            console.log(error);
            alert('Terjadi kesalahan, silahkan coba lagi: ' + error.response.data.message);
        });

        return false;
    }

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

    const fabricText = semiCustom.value?.basic?.form?.fabric?.fabricCode + ' - ' + semiCustom.value?.basic?.form?.fabric?.text;


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

    onMounted(() => {
        nextTick(() => {
            storeProducts.setProducts = products.value;
        });
    });

    const totalAllPrice = computed(() => {
        let totalProducts = 0;
        let totalSemiCustom = 0;
        let discount = coupon.value;
        let sumTotal = 0;

        if (products.value.length !== 0) {
            products.value.map(product => {
                totalProducts += product.total;
            });
        }

        if (semiCustom.value.length !== 0) {
            totalSemiCustom = semiCustom.value.totalPrice;
        }

        sumTotal = totalProducts + totalSemiCustom;

        return sumTotal - (sumTotal * discount / 100);
    });



    watch(coupon, (value) => {
        storeProducts.coupon_rtw = value;

        console.log(storeProducts.coupon_rtw);

    });

    const $emit = defineEmits(['btn-next']);

    const btnProcess = () => {
        sendOrder();
    }

    const btnBack = () => {
        if (props.onPage == 'products') {
            // $emit('btn-next', 'products');
            window.location.href = '/ready-to-wear?page=products';
        }else if (props.onPage == 'semi-custom') {
            $emit('btn-next', 'semi-custom');
        }
    }
</script>

<template>
    <div class="space-y-10">
        <section>
            <div class="flex justify-between items-center bg-primary-50 lg:px-14 lg:py-7 p-6">
                <div class="font-bold text-lg text-white lg:text-xl uppercase tracking-widest">Total shop</div>
            </div>
            <div class="lg:px-14 lg:py-10 p-6">
                <table class="w-full" v-if="products.length !== 0">
                    <thead>
                        <tr>
                            <th class="py-3 pr-6 text-left text-primary-50 uppercase name-col">Product</th>
                            <th class="px-6 py-3 text-center text-primary-50 uppercase price-col">Price</th>
                            <th class="px-6 py-3 text-center text-primary-50 uppercase">qty</th>
                            <th class="px-6 py-3 text-center text-primary-50 uppercase total-col">Total</th>
                        </tr>
                    </thead>
                    <tbody>
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
                                <div class="text-center text-secondary-50 lg:text-lg">{{ priceFormat(product.total ?? product.price) }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div :class="{ 'mt-20': products.length !== 0 }" v-if="semiCustom.length !== 0">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="py-3 pr-6 text-left text-primary-50 uppercase name-col">Semi Custom </th>
                                <th class="px-6 py-3 text-center text-primary-50 uppercase price-col">Price</th>
                                <th class="px-6 py-3 text-center text-primary-50 uppercase">qty</th>
                                <th class="px-6 py-3 text-center text-primary-50 uppercase total-col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b" >
                                <td class="py-3 pr-6 text-left text-primary-50">
                                    <div class="text-[#606060]">SEMI-CUSTOM MTM</div>
                                    <div class="text-[#A3A3A3] text-sm">{{ fabricText }}</div>
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

                <div class="flex justify-between bg-secondary px-4 pt-4 pb-3 font-bold text-lg text-primary-50 lg:text-2xl">
                    <div class="col-span-2">TOTAL AMOUNT</div>
                    <div class="mr-10 text-center">{{ priceFormat(totalAllPrice) }}</div>
                </div>
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
                            <option :selected="useProducts.getCouponRtw == 0 || coupon == 0" value="0">0%</option>
                            <option
                                v-for="(cp, index) in coupons"
                                :key="index"
                                :selected="useProducts.getCouponRtw == cp.value || coupon == cp.value" :value="cp.value">{{ cp.name }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="flex justify-between">
                <button v-if="storePage.get == 'products'" @click="btnBack()" class="flex items-center gap-2 bg-primary-50 p-4 lg:p-6 text-white tracking-widest">
                    <img class="inline-block mb-1.5 rotate-180" src="img/icons/arrw-ck-right.png" alt="">
                    <span>BACK</span>
                </button>
                <div v-if="storePage.get !== 'products'"></div>
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

.name-col{
    width: 40%;
}

.price-col{
    width: 20%;
}

.total-col{
    width: 20%;
}

</style>
