<script setup>

import { computed, defineAsyncComponent, onMounted, reactive, ref, watch } from 'vue';
import  InputBox  from '@frontend/components/utils/fields/InputBox.vue';
import { component as VueNumber } from '@coders-tm/vue-number-format';
import { measure } from '@splidejs/splide/src/js/utils';
import { usePage } from '@/frontend/store/page';
import { useCustomer } from '@/frontend/store/customer';
import { useProducts } from '@/frontend/store/product';

const storePage = usePage();
const storeCustomer = useCustomer();
const storeProducts = useProducts();

const urlParams = new URLSearchParams(window.location.search);
const url = new URL(window.location.href);

const isEditMOde = ref(false);
const editIndex = ref(null);

const number_input = {
    separator: '.',
    prefix: 'Rp ',
    precision: 0,
    masked: false,
}

const props = defineProps({
    csrf: String,
    user: Object,
    route_edit_profile: String,
    route_my_target: String,
    route_logout: String,
    data_semi_custom_outer: {
        type: Object,
        required: true
    },
    api_store_order: String,
    api_customer_size: String,
});

const form = reactive({
    fabric: {
        text: '',
        fabricCode: '',
    },
    collar: null,
    cuff: null,
});

const formSize = reactive({
    measurement: '',
    sa: {
        shoulder: '',
        backLength: '',
        sleeveLength: '',
    }
});

const selectedSize = computed(() => {
    return props.data_semi_custom_outer.sizes.data.basic.find(size => size.slug === formSize.measurement);
})

const additionalNote = ref('');
const discount = ref();
const price = ref(0);

const amount = reactive({
    price: 0,
    discount: 0,
});

const basic = computed(() => {
    let data = {
        form: form,
        formSize: formSize,
        additionalNote: additionalNote.value,
        amount: amount
    }
    return data;
})

const totalPrice = computed(() => {
    let x = parseInt(amount.price) ? parseInt(amount.price) : 0;
    let disc = parseInt(amount.discount) ? parseInt(amount.discount) : 0;
    let z = x - (x * disc / 100);
    return z;
});

const bindForm = ref(null);

const currentSection = ref(null);

onMounted(async () => {
    bindForm.value = {
        basic: basic
    }

    if (urlParams.get('page') != null) {
        storePage.currentPage = urlParams.get('page');
        const hasEditIndex = urlParams.get('edit_on_index') != null;
        const hasDuplicateIndex = urlParams.get('index') != null;

        if (!hasEditIndex && !hasDuplicateIndex) {
            storeProducts.resetDuplicateSm();
            storeProducts.setIndexSemiCustom(null);
        }
    }else{
        storePage.currentPage = currentSection.value;
        storeCustomer.customer = null;
        storeProducts.resetSemiCustom();
    }
})

watch(amount, () => {
    if (bindForm.value !== null) {
        bindForm.value.totalPrice = totalPrice.value;
    }
})

const applyPrice = () => {
    amount.price = price.value;
    amount.discount = discount.value;
}

const extend = computed(() => {
    return storePage.get == 'semi-custom-outer' ? true : false
})

const currencyFormat = (value) => {
    if (!value){
        return 0
    }
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(
        value,
    )
};

function onInputBox(val, key = 'fabric', key2 = 'fabricCode')
{
    if (key2 == 'fabricCode') {
        form[key][key2] = val;
    }
    if (key2 == 'optionNumber') {
        form[key] = {};
        form[key][key2] = val;
    }
}

const Layout = defineAsyncComponent(() => import('../../includes/Layout.vue'));
const Customer = defineAsyncComponent(() => import('../includes/CustomerData.vue'));

const btnSubmit =  async () => {
    applyPrice();

    if (form.fabric.fabricCode == null || form.fabric.text == null ||
        form.fabric.fabricCode == '' || form.fabric.text == ''
    ) {
        alert('Please fill the form Fabric Code');
    }else {
        if (totalPrice.value <= 0 && bindForm.value !== null) {
            alert('Please fill the form OR apply the price first');
        }else {
            useProducts().resetDuplicateSmOuter();
            if (isEditMOde.value && editIndex.value !== null) {
                useProducts().setCustomOuterWithKey(bindForm.value, editIndex.value);
                useProducts().setIndexSemiCustomOuter(null);
            } else {
                useProducts().setCustomOuter(bindForm.value);
            }
            window.location.href = "/cart";
        }
    }
}

const btnNext = (section, data) => {
    url.searchParams.set('page', section);
    url.searchParams.set('index', 0);
    window.history.pushState(null, '', url.toString());
    storePage.currentPage = section;
}


</script>

<template>

    <Layout :route_edit_profile="route_edit_profile" :route_my_target="route_my_target" :route_logout="route_logout" :user="user" :csrf="csrf" :extends="extend">

        <template #sidebar>
            <div :class="{'hidden': !extend}" class="top-0 sticky bg-green h-screen overflow-y-auto scroll-box">
                <div class="bg-primary-outer py-20">
                    <div class="mx-[5%] 2xl:mx-[20%] xl:mx-[10%] font-roboto text-white">
                        <div>
                            <div class="font-josefin text-2xl xl:text-4xl text-center uppercase tracking-widest">ORDER SUMMARY</div>
                            <div class="bg-white opacity-70 mx-auto my-10 w-4/6 h-0.5"></div>
                            <div>
                                <table class="*:space-y-4">
                                    <tbody>

                                        <tr>
                                            <td class="font-bold text-white uppercase tracking-widest">Fabric</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="text-white">
                                                <div>{{ form.fabric?.fabricCode ?? '-' }}</div>
                                                <div>{{ form.fabric?.text ?? '-' }}</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold text-white uppercase tracking-widest">Collar</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="text-white">{{ form.collar?.name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold text-white uppercase tracking-widest">Cuff</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="text-white">{{ form.cuff?.name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold text-white uppercase tracking-widest">Button</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="text-white">{{ form.button?.name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold text-white uppercase tracking-widest">Size</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="text-white">{{ selectedSize?.name ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mt-20">
                            <div class="font-josefin text-2xl text-center uppercase tracking-widest">Measurements</div>
                            <div class="bg-white opacity-70 mx-auto my-10 w-4/6 h-0.5"></div>
                            <div>
                                <table class="*:space-y-4">
                                    <tbody>
                                        <tr v-for="measurement in data_semi_custom_outer.sizes.measurement_key" :key="'measurement-summary-' + measurement">
                                            <td class="font-bold text-white uppercase tracking-widest">{{ measurement }}</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="text-white">{{ selectedSize?.values?.[measurement] ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold text-white uppercase tracking-widest">Special Adjustment - Shoulder</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="text-white">{{ formSize.sa.shoulder ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold text-white uppercase tracking-widest">Special Adjustment - Back Length</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="text-white">{{ formSize.sa.backLength ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="font-roboto text-white">
                    <div class="bg-secondary-50 px-[5%] 2xl:px-[20%] xl:px-[10%] py-8">
                        <div>
                            <table>
                                <tbody class="*:space-y-4 font-bold">
                                    <tr class="lg:whitespace-nowrap">
                                        <td>Base Price</td>
                                        <td class="text-center">:</td>
                                        <td class="">{{ currencyFormat(amount?.price) ?? '0' }}</td>
                                    </tr>
                                    <tr class="lg:whitespace-nowrap">
                                        <td>Discount</td>
                                        <td class="text-center">:</td>
                                        <td class="">{{ amount?.discount ?? '0' }}%</td>
                                    </tr>
                                    <tr class="lg:whitespace-nowrap">
                                        <td>Total Price</td>
                                        <td class="w-full text-center">:</td>
                                        <td class="">{{ currencyFormat(totalPrice) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="bg-black pt-3 pb-2 font-josefin font-bold lg:text-lg xl:text-xl 2xl:text-3xl text-center uppercase tracking-widest">
                        IDR {{ currencyFormat(totalPrice) ?? '0' }},-
                    </div>
                </div>
            </div>
        </template>

        <template v-if="storePage.get == 'customer-data' || storeCustomer.getCustomer == null">
            <Customer onPage="semi-custom-outer" @btn-next="btnNext"/>
        </template>

        <template v-if="storePage.get == 'semi-custom-outer' && storeCustomer.getCustomer != null">
            <div class="relative">
                <div class="flex justify-between items-center bg-primary-outer p-6 lg:px-14 lg:py-7">
                    <div class="font-bold text-white text-lg lg:text-xl uppercase tracking-widest">CUSTOM MADE OUTER SHIRT</div>
                </div>

                <!-- fabric code -->
                <div>
                    <div class="flex justify-between items-center bg-primary-outer-300 px-4 lg:px-14 py-2">
                        <div class="font-bold text-white lg:text-xl uppercase tracking-widest">01. FABRIC</div>
                    </div>
                    <div class="flex items-center gap-8 my-5 px-6 lg:px-10 xl:px-14 fabric-code">
                        <label for="fabric-code" class="text-primary-50 uppercase tracking-widest lg:whitespace-pre-wrap">fabric code</label>
                        <InputBox @update:input="onInputBox($event, 'fabric', 'fabricCode')" :inputValue="form.fabric.fabricCode" />
                        <input v-model="form.fabric.text" type="text" class="block p-2 border border-primary border-r w-full h-8 font-roboto text-gray-900 text-sm">
                    </div>
                </div>

                <!-- collar & cuff -->
                <div class="gap-4 grid grid-cols-2">
                    <!-- collar -->
                    <div>
                        <div class="flex justify-between items-center bg-primary-outer-300 px-4 lg:px-14 py-2">
                            <div class="font-bold text-white lg:text-xl uppercase tracking-widest">02. COLLAR</div>
                        </div>
                        <div class="grid grid-cols-2 my-10 px-6 lg:px-10 xl:px-14">
                            <div v-for="collar in data_semi_custom_outer.collar.data.basic">
                                <input :checked="collar.slug == form.collar?.slug" class="hidden" type="radio" v-model="form.collar" @click.native="form.collar = null" :value="collar" name="collar-basic"  :id="'collar-' + collar.slug">
                                <label class="flex flex-col justify-between items-center gap-4 px-2 rounded h-full cursor-pointer" :for="'collar-' + collar.slug">
                                    <img class="max-w-36 h-auto" :src="collar.image" alt="">
                                    <div class="font-bold text-primary-50 text-xs 2xl:text-lg text-center uppercase tracking-widest">{{ collar.name }}</div>
                                    <span class="checkbox-inner"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- cuff -->
                    <div>
                        <div class="flex justify-between items-center bg-primary-outer-300 px-4 lg:px-14 py-2">
                            <div class="font-bold text-white lg:text-xl uppercase tracking-widest">03. CUFF</div>
                        </div>
                        <div class="grid grid-cols-3 my-10 px-6 lg:px-10 xl:px-14">
                            <div v-for="cuff in data_semi_custom_outer.cuff.data.basic">
                                <input :checked="cuff.slug == form.cuff?.slug" class="hidden" type="radio" v-model="form.cuff" @click.native="form.cuff = null" :value="cuff" name="cuff-basic"  :id="'cuff-' + cuff.slug">
                                <label class="flex flex-col justify-between items-center gap-4 px-2 rounded h-full cursor-pointer" :for="'cuff-' + cuff.slug">
                                    <img class="max-w-36 h-auto" :src="cuff.image" alt="">
                                    <div class="font-bold text-primary-50 text-xs 2xl:text-lg text-center uppercase tracking-widest">{{ cuff.name }}</div>
                                    <span class="checkbox-inner"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- button -->
            <div>
                <div class="flex justify-between items-center bg-primary-outer-300 px-4 lg:px-14 py-2">
                    <div class="font-bold text-white lg:text-xl uppercase tracking-widest">04. BUTTON</div>
                </div>
                <div class="grid grid-cols-7 my-10 px-6 lg:px-10 xl:px-14">
                    <div v-for="button in data_semi_custom_outer.button.data.basic">
                        <input :checked="button.slug == form.button?.slug" class="hidden" type="radio" v-model="form.button" @click.native="form.button = null" :value="button" name="button-basic"  :id="'button-' + button.slug">
                        <label class="flex flex-col justify-between items-center gap-4 px-2 rounded h-full cursor-pointer" :for="'button-' + button.slug">
                            <img class="max-w-36 h-auto" :src="button.image" alt="">
                            <div class="font-bold text-primary-50 text-xs 2xl:text-lg text-center uppercase tracking-widest">{{ button.name }}</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- size -->
            <div>
                <div class="flex justify-between items-center bg-primary-outer-300 px-4 lg:px-14 py-2">
                    <div class="font-bold text-white lg:text-xl uppercase tracking-widest">05. SIZE</div>
                </div>
                <div class="my-10 px-6 lg:px-10 xl:px-14 overflow-x-auto">

                    <table class="border border-primary-outer min-w-full border-collapse table-fixed">
                        <thead>
                            <tr class="bg-white">
                                <th>

                                </th>
                                <th
                                    v-for="size in data_semi_custom_outer.sizes.data.basic"
                                    :key="'size-option-' + size.slug"
                                    class="px-4 py-3 border border-primary-outer text-center whitespace-nowrap"
                                >
                                    <div class="flex justify-center">
                                        <input
                                            v-model="formSize.measurement"
                                            :value="size.slug"
                                            type="radio"
                                            name="size-option"
                                            class="hidden"
                                            :id="'size-option-' + size.slug"
                                        >
                                        <label class="cursor-pointer" :for="'size-option-' + size.slug">
                                            <span class="checkbox-inner"></span>
                                        </label>
                                    </div>
                                </th>
                            </tr>
                            <tr class="bg-primary-outer text-white">
                                <th class="px-4 py-3 border border-primary-outer w-44 text-left uppercase tracking-widest whitespace-nowrap">Measurement</th>
                                <th
                                    v-for="size in data_semi_custom_outer.sizes.data.basic"
                                    :key="'size-head-' + size.slug"
                                    class="px-4 py-3 border border-primary-outer text-center uppercase tracking-widest whitespace-nowrap"
                                >
                                    {{ size.name }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="measurement in data_semi_custom_outer.sizes.measurement_key"
                                :key="'measurement-' + measurement"
                                class="even:bg-primary-outer-100/30 odd:bg-white"
                            >
                                <td class="px-4 py-3 border border-primary-outer font-bold text-primary-50 whitespace-nowrap">
                                    {{ measurement }}
                                </td>
                                <td
                                    v-for="size in data_semi_custom_outer.sizes.data.basic"
                                    :key="'size-value-' + size.slug + '-' + measurement"
                                    class="px-4 py-3 border border-primary-outer text-primary-50 text-center whitespace-nowrap"
                                >
                                    {{ size.values?.[measurement] ?? '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="grid grid-cols-2 xl:grid-cols-4 mt-10 mb-10 *:px-2 *:pt-2 *:pb-1 text-primary-50 text-sm tracking-widest whitespace-pre">
                        <div class="border-2 border-primary-50">SPECIAL ADJUSTMENT</div>
                        <div class="flex border-primary-50 border-y-2 border-r-2">
                            <div>SHOULDER :</div>
                            <div>
                                <input v-model="formSize.sa.shoulder" type="text" class="w-full font-roboto text-center">
                            </div>
                        </div>
                        <div class="flex border-primary-50 border-y-2 border-r-2 max-xl:border-l-2">
                            <div>BACK LENGTH :</div>
                            <div>
                                <input v-model="formSize.sa.backLength" type="text" class="w-full font-roboto text-center">
                            </div>
                        </div>
                        <div class="flex border-primary-50 border-y-2 border-r-2">
                            <div>SLEEVE LENGTH :</div>
                            <div>
                                <input v-model="formSize.sa.sleeveLength" type="text" class="w-full font-roboto text-center">
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Additional Notes-->
            <div class="mb-32">
                <div class="flex justify-between items-center bg-primary-outer-300 px-4 lg:px-14 py-2">
                    <div class="font-bold text-white lg:text-xl uppercase tracking-widest">ADDITIONAL NOTES</div>
                </div>
                <div class="gap-3 grid grid-cols-5 my-10 px-6 lg:px-10 xl:px-14">
                    <div class="col-span-3">
                        <textarea v-model="additionalNote" class="p-2 border-2 border-primary-outer w-full h-full font-roboto placeholder:font-josefin placeholder:tracking-widest placeholder-primary-50" name="" id="" placeholder="NOTE"></textarea>
                    </div>
                    <div class="space-y-2 col-span-2">
                        <input v-model="discount" type="number"  class="px-4 pt-2 pb-1 border-2 border-primary-outer w-full text-primary-50 number-input" placeholder="DISCOUNT"/>
                        <VueNumber v-model.lazy="price" v-bind="number_input" class="px-4 pt-2 pb-1 border-2 border-primary-outer w-full text-primary-50 number-input" placeholder="RP"></VueNumber>

                        <div>
                            <button @click="applyPrice()" class="bg-secondary px-5 pt-3 pb-2 w-full text-primary-outer text-center">APPLY PRICE</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="right-0 bottom-0 absolute">
                <div class="flex items-end">
                    <!-- <button v-if="hasSemiCustom" @click="goToCart" class="flex items-center gap-2 bg-secondary-50 p-6 h-fit text-white tracking-widest">
                        <span>CANCEL & SUBMIT</span>
                        <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
                    </button>

                    <div>
                        <button v-if="hasDuplicate && !isEditMOde" @click="duplicateSemiCustom()" class="flex justify-between items-center gap-2 bg-primary-50 p-6 w-full text-white tracking-widest">
                            <span>DUPLICATE</span>
                            <img class="inline-block rotate-90" src="img/icons/arrw-ck-right.png" alt="">
                        </button>
                        <button v-if="!isEditMOde" @click="addCustomRequest()" class="flex items-center gap-2 bg-primary-300 p-6 h-fit text-white tracking-widest">
                            <span>ADD NEW CUSTOM REQUEST </span>
                            <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
                        </button>
                    </div> -->

                    <button @click="btnSubmit()" class="flex items-center gap-2 bg-secondary-50 p-6 h-fit text-white tracking-widest">
                        <span>SUBMIT</span>
                        <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
                    </button>

                </div>
            </div>
        </template>





    </Layout>

</template>

<style scoped>
    input[type="radio"] + label span.checkbox-inner {
        @apply border-primary-outer;
    }
    input[type="radio"]:checked + label span.checkbox-inner {
        @apply bg-primary-outer border-primary-outer;
        color: #fff;
        background-image: url("data:image/svg+xml,%3C%3Fxml version='1.0' encoding='UTF-8'%3F%3E%3Csvg width='14px' height='10px' viewBox='0 0 14 10' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'%3E%3C!-- Generator: Sketch 59.1 (86144) - https://sketch.com --%3E%3Ctitle%3Echeck%3C/title%3E%3Cdesc%3ECreated with Sketch.%3C/desc%3E%3Cg id='Page-1' stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'%3E%3Cg id='ios_modification' transform='translate(-27.000000, -191.000000)' fill='%23FFFFFF' fill-rule='nonzero'%3E%3Cg id='Group-Copy' transform='translate(0.000000, 164.000000)'%3E%3Cg id='ic-check-18px' transform='translate(25.000000, 23.000000)'%3E%3Cpolygon id='check' points='6.61 11.89 3.5 8.78 2.44 9.84 6.61 14 15.56 5.05 14.5 4'%3E%3C/polygon%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        background-size: 14px 10px;
    }
    .checkbox-inner {
        @apply flex justify-center items-center border border-primary-outer size-7 text-transparent;
        background: transparent no-repeat center;
    }
</style>
