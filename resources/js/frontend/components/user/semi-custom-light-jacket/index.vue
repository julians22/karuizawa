<script setup>
import { isNull } from 'lodash';
import { useCustomer } from '@/frontend/store/customer';
import { usePage } from '@/frontend/store/page';
import { useProducts } from '@/frontend/store/product';
import Layout from '../../includes/Layout.vue';
import InputBox from '../../utils/fields/InputBox.vue';
import { computed, defineAsyncComponent, onMounted, reactive, ref, watch } from 'vue';
import { component as VueNumber } from '@coders-tm/vue-number-format';

const storePage = usePage();
const storeCustomer = useCustomer();
const storeProducts = useProducts();

const isEditMOde = ref(false);
const editIndex = ref(null);

const urlParams = new URLSearchParams(window.location.search);
const url = new URL(window.location.href);

const rawEditIndex = urlParams.get('edit_on_index');
if (rawEditIndex !== null) {
    const parsedEditIndex = parseInt(rawEditIndex, 10);
    const existingData = storeProducts.semi_custom_light_jacket?.[parsedEditIndex];
    if (!Number.isNaN(parsedEditIndex) && existingData) {
        isEditMOde.value = true;
        editIndex.value = parsedEditIndex;
        storeProducts.setIndexSemiCustomLightJacket(parsedEditIndex);
        storeProducts.setDuplicateSmLightJacket(existingData);
    }
}

const number_input = {
    separator: '.',
    prefix: 'Rp ',
    precision: 0,
    masked: false,
};

const props = defineProps({
    csrf: String,
    user: Object,
    route_edit_profile: String,
    route_my_target: String,
    route_logout: String,
    data_semi_custom_light_jacket: {
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
    button: null,
    button_hole: null,
    button_sewing: null,
    embroidery: {},
});

const formSize = reactive({
    measurement: '',
    measurement_values: {},
    sa: {
        shoulder: '',
        chest: '',
        backLength: '',
        sleeveLength: '',
    },
    actualMeasurement: {
        values: props.data_semi_custom_light_jacket.sizes.measurement_key.reduce((acc, key) => {
            acc[key] = '';
            return acc;
        }, {})
    },
    order: '',
});

const selectedSize = computed(() => {
    return props.data_semi_custom_light_jacket.sizes.data.basic.find(size => size.slug === formSize.measurement);
});

const additionalNote = ref('');
const addressNote = ref('');
const discount = ref();
const price = ref(0);

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

const amount = reactive({
    price: 0,
    discount: 0,
});

const embroidery = ref({
    slug: 'embroidery',
    color: null,
    fontType: null,
    initialName: {
        x: '',
        y: '',
        dot: '',
        z: ''
    },
    price: 0,
    isHasPrice: function () {
        if (
            (
                this.initialName.x == '' &&
                this.initialName.y == '' &&
                this.initialName.dot == '' &&
                this.initialName.z == ''
            ) &&
            (isNull(this.color) || this.color == '') &&
            (isNull(this.fontType) || this.fontType == '')
        ) {
            return false;
        } else {
            return true;
        }
    }
});

watch(embroidery, (items) => {
    if (!items.isHasPrice()) {
        items.price = 0;
    } else {
        items.price = 50000; //default price for embroidery if any of the fields are filled
    }
    form.embroidery = items;
}, { deep: true });

// Option price: embroidery + button hole + button thread
const optionPrice = computed(() => {
    let total = 0;
    total += embroidery.value.price ?? 0;
    total += form.button_hole?.price ?? 0;
    total += form.button_sewing?.price ?? 0;
    return total;
});

const basic = computed(() => ({
    form: form,
    formSize: formSize,
    additionalNote: additionalNote.value,
    addressNote: addressNote.value,
    amount: amount,
    optionPrice: optionPrice.value,
}));

const totalPrice = computed(() => {
    let base = parseInt(amount.price) ? parseInt(amount.price) : 0;
    let disc = parseInt(amount.discount) ? parseInt(amount.discount) : 0;
    let baseAfterDiscount = base - (base * disc / 100);
    return baseAfterDiscount + optionPrice.value;
});

const bindForm = ref(null);
const currentSection = ref(null);

onMounted(async () => {
    bindForm.value = {
        basic: basic,
    };

    const hasEditIndex = urlParams.get('edit_on_index') != null;
    const hasDuplicateIndex = urlParams.get('index') != null;

    if (hasEditIndex || hasDuplicateIndex) {
        const duplicateData = storeProducts.getDuplicateSmLightJacket;
        const basicData = duplicateData?.basic ?? duplicateData;

        if (basicData?.form) {
            form.fabric.text = basicData.form.fabric?.text || '';
            form.fabric.fabricCode = basicData.form.fabric?.fabricCode || '';
            form.button = basicData.form.button || null;
            form.button_hole = basicData.form.button_hole || null;
            form.button_sewing = basicData.form.button_sewing || null;

            if (basicData.form.embroidery && typeof basicData.form.embroidery === 'object' && basicData.form.embroidery.slug) {
                embroidery.value.color = basicData.form.embroidery.color || null;
                embroidery.value.fontType = basicData.form.embroidery.fontType || null;
                embroidery.value.initialName = basicData.form.embroidery.initialName || { x: '', y: '', dot: '', z: '' };
                embroidery.value.price = basicData.form.embroidery.price || 0;
            }
        }

        if (basicData?.formSize) {
            formSize.measurement = basicData.formSize.measurement || '';
            formSize.measurement_values = basicData.formSize.measurement_values || {};
            formSize.sa.shoulder = basicData.formSize.sa?.shoulder || '';
            formSize.sa.backLength = basicData.formSize.sa?.backLength || '';
            formSize.sa.sleeveLength = basicData.formSize.sa?.sleeveLength || '';
            formSize.order = basicData.formSize.order || '';

            if (basicData.formSize.actualMeasurement?.values) {
                formSize.actualMeasurement.values = {
                    ...formSize.actualMeasurement.values,
                    ...basicData.formSize.actualMeasurement.values,
                };
            }
        }

        additionalNote.value = basicData?.additionalNote || '';
        addressNote.value = basicData?.addressNote || '';
        amount.price = basicData?.amount?.price || 0;
        amount.discount = basicData?.amount?.discount || 0;
        price.value = amount.price;
        discount.value = amount.discount;
    }

    if (urlParams.get('page') != null) {
        storePage.currentPage = urlParams.get('page');
        if (!hasEditIndex && !hasDuplicateIndex) {
            storeProducts.resetDuplicateSmLightJacket();
            storeProducts.setIndexSemiCustomLightJacket(null);
        }
    } else {
        storePage.currentPage = currentSection.value;
        storeCustomer.customer = null;
        storeProducts.resetSemiCustomLightJacket();
    }
});

watch(amount, () => {
    if (bindForm.value !== null) {
        bindForm.value.totalPrice = totalPrice.value;
    }
});

const applyPrice = () => {
    amount.price = price.value;
    amount.discount = discount.value;
};

const extend = computed(() => {
    return storePage.get == 'semi-custom-light-jacket';
});

const currencyFormat = (value) => {
    if (!value) { return 0; }
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
};

const onInputIntialName = (val, key = 'z') => {
    embroidery.value.initialName[key] = val;
};

const btnSubmit = async () => {
    applyPrice();
    const selectedSizeValue = props.data_semi_custom_light_jacket.sizes.data.basic.find(size => size.slug === formSize.measurement);
    formSize.measurement_values = selectedSizeValue ? selectedSizeValue.values : {};

    if (!form.fabric.fabricCode || !form.fabric.text) {
        alert('Please fill the form Fabric Code');
    } else {
        if (totalPrice.value <= 0 && bindForm.value !== null) {
            alert('Please fill the form OR apply the price first');
        } else {
            bindForm.value.totalPrice = totalPrice.value;
            useProducts().resetDuplicateSmLightJacket();
            if (isEditMOde.value && editIndex.value !== null) {
                useProducts().setCustomLightJacketWithKey(bindForm.value, editIndex.value);
                useProducts().setIndexSemiCustomLightJacket(null);
            } else {
                useProducts().setCustomLightJacket(bindForm.value);
            }
            window.location.href = '/cart';
        }
    }
};

const addCustomRequest = () => {
    applyPrice();
    const selectedSizeValue = props.data_semi_custom_light_jacket.sizes.data.basic.find(size => size.slug === formSize.measurement);
    formSize.measurement_values = selectedSizeValue ? selectedSizeValue.values : {};

    if (!form.fabric.fabricCode || !form.fabric.text) {
        alert('Please fill the form Fabric Code');
    } else {
        if (totalPrice.value <= 0 && bindForm.value !== null) {
            alert('Please fill the form OR apply the price first');
        } else {
            bindForm.value.totalPrice = totalPrice.value;
            useProducts().resetDuplicateSmLightJacket();
            if (isEditMOde.value && editIndex.value !== null) {
                useProducts().setCustomLightJacketWithKey(bindForm.value, editIndex.value);
                useProducts().setIndexSemiCustomLightJacket(null);
            } else {
                useProducts().setCustomLightJacket(bindForm.value);
            }
            window.location.href = `/semi-custom-light-jacket?page=semi-custom-light-jacket&index=${storeProducts.semi_custom_light_jacket.length}`;
        }
    }
};

const duplicateSemiCustom = () => {
    applyPrice();
    const selectedSizeValue = props.data_semi_custom_light_jacket.sizes.data.basic.find(size => size.slug === formSize.measurement);
    formSize.measurement_values = selectedSizeValue ? selectedSizeValue.values : {};

    if (!form.fabric.fabricCode || !form.fabric.text) {
        alert('Please fill the form Fabric Code');
    } else {
        if (totalPrice.value <= 0 && bindForm.value !== null) {
            alert('Please fill the form OR apply the price first');
        } else {
            bindForm.value.totalPrice = totalPrice.value;
            const indexParam = urlParams.get('index');
            const fallbackIndex = storeProducts.semi_custom_light_jacket.length;
            const nextIndex = Number.isNaN(parseInt(indexParam, 10)) ? fallbackIndex : parseInt(indexParam, 10);

            storeProducts.setIndexSemiCustomLightJacket(nextIndex);
            useProducts().setCustomLightJacket(bindForm.value);
            useProducts().setDuplicateSmLightJacket(bindForm.value);

            setTimeout(() => {
                window.location.href = `/semi-custom-light-jacket?page=semi-custom-light-jacket&index=${nextIndex + 1}`;
            });
            alert('Success');
        }
    }
};

const goToCart = () => {
    window.location.href = '/cart';
};

const cancelEdit = () => {
    isEditMOde.value = false;
    editIndex.value = null;
    storeProducts.resetDuplicateSmLightJacket();
    storeProducts.setIndexSemiCustomLightJacket(null);
    window.location.href = '/cart';
};

const hasDuplicate = computed(() => {
    return totalPrice.value > 0;
});

const hasSemiCustomLightJacket = computed(() => {
    return storeProducts.semi_custom_light_jacket.length > 0;
});

const Customer = defineAsyncComponent(() => import('../includes/CustomerData.vue'));

const btnNext = (section) => {
    url.searchParams.set('page', section);
    url.searchParams.set('index', 0);
    window.history.pushState(null, '', url.toString());
    storePage.currentPage = section;
};

</script>

<template>

    <Layout :route_edit_profile="route_edit_profile" :route_my_target="route_my_target" :route_logout="route_logout" :user="user" :csrf="csrf" :extends="extend">
        <template #sidebar>
            <div :class="{'hidden': !extend}" class="top-0 sticky bg-green h-screen overflow-y-auto scroll-box">
                <div class="bg-primary-light-jacket-100 py-20">
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
                                            <td class="font-bold text-white uppercase tracking-widest">Button</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="text-white">{{ form.button?.name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold text-white uppercase tracking-widest">Button Hole</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="text-white">{{ form.button_hole?.name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold text-white uppercase tracking-widest">Button Thread</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="text-white">{{ form.button_sewing?.name ?? '-' }}</td>
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
                                        <tr v-for="measurement in data_semi_custom_light_jacket.sizes.measurement_key" :key="'measurement-summary-' + measurement">
                                            <td class="font-bold text-white uppercase tracking-widest">{{ measurement }}</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="text-white">{{ selectedSize?.values?.[measurement] ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold text-white uppercase tracking-widest">Order Type</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="text-white">{{ formSize.order ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="font-roboto text-white">
                    <div class="bg-secondary-50 px-[5%] 2xl:px-[20%] xl:px-[10%] py-8">
                        <table>
                            <tbody class="*:space-y-4 font-bold">
                                <tr class="lg:whitespace-nowrap">
                                    <td>Base Price</td>
                                    <td class="text-center">:</td>
                                    <td>{{ currencyFormat(amount?.price) ?? '0' }}</td>
                                </tr>
                                <tr class="lg:whitespace-nowrap">
                                    <td>Discount</td>
                                    <td class="text-center">:</td>
                                    <td>{{ amount?.discount ?? '0' }}%</td>
                                </tr>
                                <tr class="lg:whitespace-nowrap">
                                    <td>Option Price</td>
                                    <td class="text-center">:</td>
                                    <td>{{ currencyFormat(optionPrice) }}</td>
                                </tr>
                                <tr class="lg:whitespace-nowrap">
                                    <td>Total Price</td>
                                    <td class="text-center">:</td>
                                    <td>{{ currencyFormat(totalPrice) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-black pt-3 pb-2 font-josefin font-bold lg:text-lg xl:text-xl 2xl:text-3xl text-center uppercase tracking-widest">
                        IDR {{ currencyFormat(totalPrice) ?? '0' }},-
                    </div>
                </div>
            </div>
        </template>

        <template v-if="storePage.get == 'customer-data' || storeCustomer.getCustomer == null">
            <Customer onPage="semi-custom-light-jacket" @btn-next="btnNext"/>
        </template>

        <template v-if="storePage.get == 'semi-custom-light-jacket' && storeCustomer.getCustomer != null">
            <div class="relative">
                <div class="flex justify-between items-center bg-primary-light-jacket-100 p-6 lg:px-14 lg:py-7">
                    <div class="font-bold text-white text-lg lg:text-xl uppercase tracking-widest">CUSTOM MADE LIGHT JACKET</div>
                </div>

                 <!-- fabric code -->
                <div>
                    <div class="flex justify-between items-center bg-primary-light-jacket px-4 lg:px-14 py-2">
                        <div class="font-bold text-white lg:text-xl uppercase tracking-widest">01. FABRIC</div>
                    </div>
                    <div class="flex items-center gap-8 my-5 px-6 lg:px-10 xl:px-14 fabric-code">
                        <label for="fabric-code" class="text-primary-50 uppercase tracking-widest lg:whitespace-pre-wrap">fabric code</label>
                        <InputBox @update:input="onInputBox($event, 'fabric', 'fabricCode')" :inputValue="form.fabric.fabricCode" />
                        <input v-model="form.fabric.text" type="text" class="block p-2 border border-primary border-r w-full h-8 font-roboto text-gray-900 text-sm">
                    </div>
                </div>

                <!-- button -->
                <div class="flex justify-between items-center bg-primary-light-jacket px-4 lg:px-14 py-2">
                    <div class="font-bold text-white lg:text-xl uppercase tracking-widest">02. BUTTON</div>
                </div>
                <div class="gap-4 grid grid-cols-5 xl:grid-cols-9 my-10 px-6 lg:px-10 xl:px-14">
                    <div v-for="button in data_semi_custom_light_jacket.button.data.basic">
                        <input class="hidden" type="radio" name="button-basic" :id="`button-${button.slug}`" v-model="form.button" @click.native="form.button = null" :value="button">
                        <label class="flex flex-col justify-between items-center px-2 rounded h-full cursor-pointer" :for="`button-${button.slug}`">
                            <img class="h-auto" :src="button.image" alt="">
                            <div class="font-bold text-primary-light-jacket-50 text-xs 2xl:text-lg text-center uppercase tracking-widest">{{ button.name }}</div>
                            <span class="mt-4 checkbox-inner"></span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center gap-12 mx-20 my-10">
                    <div class="font-bold text-primary-50 text-xs 2xl:text-lg uppercase tracking-widest">OPTION</div>
                    <div class="flex font-roboto">
                        <InputBox :digitCount="2" @update:input="onInputBox($event, 'button', 'optionNumber')" :inputValue="form.button?.optionNumber"/>
                    </div>
                </div>

                <!-- size -->
                <div>
                    <div class="flex justify-between items-center bg-primary-light-jacket px-4 lg:px-14 py-2">
                        <div class="font-bold text-white lg:text-xl uppercase tracking-widest">03. SIZE</div>
                    </div>
                    <div class="gap-2 grid grid-cols-3 xl:grid-cols-4 my-10 px-6 lg:px-10 xl:px-14">
                        <div>
                            <input v-model="formSize.order" class="hidden" value="1. NEW ORDER" type="radio" name="size" :id="`new-order`">
                            <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`new-order`">
                                <div class="font-bold text-primary-50 text-xs 2xl:text-lg text-center uppercase tracking-widest">1. NEW ORDER</div>
                                <span class="checkbox-inner"></span>
                            </label>
                        </div>
                        <div class="justify-self-center xl:col-span-2">
                            <input v-model="formSize.order" class="hidden" type="radio" value="2. REPEAT ORDER" name="size" :id="`repeat-order`">
                            <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`repeat-order`">
                                <div class="font-bold text-primary-50 text-xs 2xl:text-lg text-center uppercase tracking-widest">2. REPEAT ORDER</div>
                                <span class="checkbox-inner"></span>
                            </label>
                        </div>
                        <div>
                            <input v-model="formSize.order" class="hidden" type="radio" name="size" value="3. GARMENT SAMPLE" :id="`garment-sample`">
                            <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`garment-sample`">
                                <div class="font-bold text-primary-50 text-xs 2xl:text-lg text-center uppercase tracking-widest">3. GARMENT SAMPLE</div>
                                <span class="checkbox-inner"></span>
                            </label>
                        </div>
                    </div>
                    <div class="my-10 px-6 lg:px-10 xl:px-14 overflow-x-auto">

                        <table class="border border-primary-outer min-w-full border-collapse table-fixed">
                            <thead>
                                <tr class="bg-white">
                                    <th>

                                    </th>
                                    <th
                                        v-for="size in data_semi_custom_light_jacket.sizes.data.basic"
                                        :key="'size-option-' + size.slug"
                                        class="px-4 py-3 border border-primary-light-jacket text-center whitespace-nowrap"
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
                                    <th class="px-4 py-3 border border-primary-light-jacket w-44 text-left uppercase tracking-widest whitespace-nowrap">Measurement</th>
                                    <th
                                        v-for="size in data_semi_custom_light_jacket.sizes.data.basic"
                                        :key="'size-head-' + size.slug"
                                        class="px-4 py-3 border border-primary-light-jacket text-center uppercase tracking-widest whitespace-nowrap"
                                    >
                                        {{ size.name }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="measurement in data_semi_custom_light_jacket.sizes.measurement_key"
                                    :key="'measurement-' + measurement"
                                    class="even:bg-primary-light-jacket-100/30 odd:bg-white"
                                >
                                    <td class="px-4 py-3 border border-primary-outer font-bold text-primary-light-jacket-100 whitespace-nowrap">
                                        {{ measurement }}
                                    </td>
                                    <td
                                        v-for="size in data_semi_custom_light_jacket.sizes.data.basic"
                                        :key="'size-value-' + size.slug + '-' + measurement"
                                        class="px-4 py-3 border border-primary-outer w-48 text-primary-light-jacket-100 text-center whitespace-nowrap"
                                    >
                                        <div>
                                            {{ size.values[measurement] }}
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Actual Measurements -->
                        <div class="mt-10 mb-10 text-primary-50 text-sm tracking-widest whitespace-pre">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="p-2 border-2 border-primary-50">MEASURE</th>
                                        <th v-for="measurement in data_semi_custom_light_jacket.sizes.actual_measurement_key" :key="'actual-measurement-' + measurement" class="p-2 border-2 border-primary-50 text-center">
                                            {{ measurement }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="p-2 border-2 border-primary-50 font-bold">ACTUAL</td>
                                        <td v-for="measurement in data_semi_custom_light_jacket.sizes.actual_measurement_key" :key="'actual-measurement-value-' + measurement" class="p-2 border-2 border-primary-50 text-center">
                                            <input v-model="formSize.actualMeasurement.values[measurement]" type="text" inputmode="numeric" class="w-full font-roboto text-center">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                        <!-- Special Adjustments -->
                        <div class="grid grid-cols-2 xl:grid-cols-4 mt-10 mb-10 *:px-2 *:pt-2 *:pb-1 text-primary-50 text-sm tracking-widest whitespace-pre">
                            <div class="border-2 border-primary-50">SPECIAL ADJUSTMENT</div>
                            <div class="flex border-primary-50 border-y-2 border-r-2">
                                <div>SHOULDER :</div>
                                <div>
                                    <input v-model="formSize.sa.shoulder"  type="text" inputmode="numeric" class="w-full font-roboto text-center">
                                </div>
                            </div>
                            <!-- Chest -->
                            <div class="flex border-primary-50 border-y-2 border-r-2">
                                <div>CHEST :</div>
                                <div>
                                    <input v-model="formSize.sa.chest"  type="text" inputmode="numeric" class="w-full font-roboto text-center">
                                </div>
                            </div>
                            <div class="flex border-primary-50 border-y-2 border-r-2 max-xl:border-l-2">
                                <div>BACK LENGTH :</div>
                                <div>
                                    <input v-model="formSize.sa.backLength"  type="text" inputmode="numeric" class="w-full font-roboto text-center">
                                </div>
                            </div>
                            <div class="flex border-primary-50 border-y-2 border-r-2">
                                <div>SLEEVE LENGTH :</div>
                                <div>
                                    <input v-model="formSize.sa.sleeveLength"  type="text" inputmode="numeric" class="w-full font-roboto text-center">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Notes-->
                <div class="mb-24">
                    <div class="flex justify-between items-center bg-primary-light-jacket px-4 lg:px-14 py-2">
                        <div class="font-bold text-white lg:text-xl uppercase tracking-widest">ADDITIONAL NOTES</div>
                    </div>
                    <div class="gap-3 grid grid-cols-5 my-10 px-6 lg:px-10 xl:px-14">
                        <div class="col-span-3">
                            <textarea v-model="addressNote" rows="2" class="p-2 border-2 border-primary-50 w-full font-roboto placeholder:font-josefin placeholder:tracking-widest placeholder-primary-50" name="" id="" placeholder="ADDRESS"></textarea>
                            <textarea v-model="additionalNote" class="p-2 border-2 border-primary-outer w-full h-[150px] font-roboto placeholder:font-josefin placeholder:tracking-widest placeholder-primary-50" name="" id="" placeholder="NOTE"></textarea>
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

            </div>

            <div class="relative mb-48">

                <!-- button hole -->
                <div>
                    <div class="flex justify-between items-center bg-primary-light-jacket px-4 lg:px-14 py-2">
                        <div class="font-bold text-white lg:text-xl uppercase tracking-widest">Button Hole</div>
                    </div>
                    <div class="gap-y-4 grid grid-cols-6 my-10 px-6 lg:px-10 xl:px-14">
                        <div v-for="button_hole in data_semi_custom_light_jacket.button_hole.data.option.option_1">
                            <input :checked="button_hole.slug == form.button_hole?.slug" class="hidden" type="radio" v-model="form.button_hole" @click.native="form.button_hole = null" :value="button_hole" name="button-hole-option"  :id="'button-hole-' + button_hole.slug">
                            <label class="flex flex-col justify-between items-center gap-4 px-2 rounded h-full cursor-pointer" :for="'button-hole-' + button_hole.slug">
                                <img class="max-w-36 h-auto" :src="button_hole.image" alt="">
                                <div class="font-bold text-primary-50 text-xs 2xl:text-lg text-center uppercase tracking-widest">{{ button_hole.name }}</div>
                                <span class="checkbox-inner"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- button thread -->
                <div>
                    <div class="flex justify-between items-center bg-primary-light-jacket px-4 lg:px-14 py-2">
                        <div class="font-bold text-white lg:text-xl uppercase tracking-widest">Button Thread</div>
                    </div>
                    <div class="gap-y-4 grid grid-cols-6 my-10 px-6 lg:px-10 xl:px-14">
                        <div v-for="button_sewing in data_semi_custom_light_jacket.button_sewing.data.option.option_1">
                            <input :checked="button_sewing.slug == form.button_sewing?.slug" class="hidden" type="radio" v-model="form.button_sewing" @click.native="form.button_sewing = null" :value="button_sewing" name="button-sewing-option"  :id="'button-sewing-' + button_sewing.slug">
                            <label class="flex flex-col justify-between items-center gap-4 px-2 rounded h-full cursor-pointer" :for="'button-sewing-' + button_sewing.slug">
                                <img class="max-w-28 h-auto" :src="button_sewing.image" alt="">
                                <div class="font-bold text-primary-50 text-xs 2xl:text-lg text-center uppercase tracking-widest">{{ button_sewing.name }}</div>
                                <span class="checkbox-inner"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- embroidery -->
                <div>
                    <div class="flex justify-between items-center bg-primary-light-jacket px-4 lg:px-14 py-2">
                        <div class="font-bold text-white lg:text-xl uppercase tracking-widest">embroidery</div>
                    </div>
                    <div class="px-3 py-2">
                        <div class="gap-x-4 grid grid-cols-2">
                            <div>
                                <div class="flex justify-between items-center bg-primary-light-jacket-100 px-4 lg:px-10 py-2">
                                    <div class="font-bold text-white uppercase tracking-widest">Initial / full name</div>
                                </div>
                                <div class="col-span-3 p-4">
                                    <div class="flex max-xl:flex-wrap items-end gap-4">
                                        <div class="flex items-end font-roboto">
                                            <input v-model="embroidery.initialName.x" type="text" maxlength="1" class="block p-2 border border-primary-50 size-10 text-gray-900 text-sm text-center">
                                            <input v-model="embroidery.initialName.dot" type="text" maxlength="1" class="block border-primary-50 border-y w-8 h-6 text-gray-900 text-sm text-center">
                                            <input v-model="embroidery.initialName.y" type="text" maxlength="1" class="block p-2 border-primary-50 border-y border-r border-l size-10 text-gray-900 text-sm text-center">
                                            <InputBox :digitCount="9" @update:input="onInputIntialName($event)" :inputValue="embroidery.initialName?.z"/>
                                            <input v-model="embroidery.initialName.note" type="text" maxlength="50" class="block ml-2 p-2 border border-primary-50 w-full text-gray-900 text-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between items-center bg-primary-light-jacket-100 px-4 lg:px-10 py-2">
                                    <div class="font-bold text-white uppercase tracking-widest">fonts</div>
                                </div>
                                <div class="gap-y-4 grid grid-cols-3 my-10 px-6 lg:px-10 xl:px-14">
                                    <div v-for="font in data_semi_custom_light_jacket.embroidery.data.options.fonts">
                                        <input :checked="font.slug == embroidery.fontType?.slug" class="hidden" type="radio" v-model="embroidery.fontType" @click.native="embroidery.fontType = null" :value="font" name="font-option"  :id="'font-' + font.slug">
                                        <label class="flex flex-col justify-between items-center gap-4 px-2 rounded h-full cursor-pointer" :for="'font-' + font.slug">
                                            <img class="max-w-28 h-auto" :src="font.image" alt="">
                                            <div class="font-bold text-primary-50 text-xs 2xl:text-lg text-center uppercase tracking-widest">{{ font.name }}</div>
                                            <span class="checkbox-inner"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center bg-primary-light-jacket-100 px-4 lg:px-10 py-2">
                            <div class="font-bold text-white uppercase tracking-widest">font colors</div>
                        </div>
                        <div class="gap-y-4 grid grid-cols-6 my-10 px-6 lg:px-10 xl:px-14">
                            <div v-for="color in data_semi_custom_light_jacket.embroidery.data.options.colors">
                                <input :checked="color.slug == embroidery.color?.slug" class="hidden" type="radio" v-model="embroidery.color" @click.native="embroidery.color = null" :value="color" name="color-option"  :id="'color-' + color.slug">
                                <label class="flex flex-col justify-between items-center gap-4 px-2 rounded h-full cursor-pointer" :for="'color-' + color.slug">
                                    <img class="max-w-28 h-auto" :src="color.image" alt="">
                                    <div class="font-bold text-primary-50 text-xs 2xl:text-lg text-center uppercase tracking-widest">{{ color.name }}</div>
                                    <span class="checkbox-inner"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="right-0 bottom-0 absolute">
                <div class="flex items-end">
                    <button v-if="hasSemiCustomLightJacket || isEditMOde" @click="isEditMOde ? cancelEdit() : goToCart()" class="flex items-center gap-2 bg-secondary-50 p-6 h-fit text-white tracking-widest">
                        <span>{{ isEditMOde ? 'CANCEL' : 'CANCEL & SUBMIT' }}</span>
                        <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
                    </button>

                    <div>
                        <button v-if="hasDuplicate" @click="duplicateSemiCustom()" class="flex justify-between items-center gap-2 bg-primary-50 p-6 w-full text-white tracking-widest">
                            <span>DUPLICATE</span>
                            <img class="inline-block rotate-90" src="img/icons/arrw-ck-right.png" alt="">
                        </button>
                        <button v-if="!isEditMOde" @click="addCustomRequest()" class="flex items-center gap-2 bg-primary-300 p-6 h-fit text-white tracking-widest">
                            <span>ADD NEW CUSTOM REQUEST</span>
                            <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
                        </button>
                    </div>

                    <button @click="btnSubmit()" class="flex items-center gap-2 p-6 h-fit text-white tracking-widest" :class="isEditMOde ? 'bg-primary-50' : 'bg-secondary-50'">
                        <span>{{ isEditMOde ? 'UPDATE' : 'SUBMIT' }}</span>
                        <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
                    </button>
                </div>
            </div>
        </template>

    </Layout>

</template>

<style scoped>
    input[type="radio"] + label span.checkbox-inner {
        @apply border-primary-light-jacket-50;
    }
    input[type="radio"]:checked + label span.checkbox-inner {
        @apply bg-primary-light-jacket-50 border-primary-light-jacket-50;
        color: #fff;
        background-image: url("data:image/svg+xml,%3C%3Fxml version='1.0' encoding='UTF-8'%3F%3E%3Csvg width='14px' height='10px' viewBox='0 0 14 10' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'%3E%3C!-- Generator: Sketch 59.1 (86144) - https://sketch.com --%3E%3Ctitle%3Echeck%3C/title%3E%3Cdesc%3ECreated with Sketch.%3C/desc%3E%3Cg id='Page-1' stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'%3E%3Cg id='ios_modification' transform='translate(-27.000000, -191.000000)' fill='%23FFFFFF' fill-rule='nonzero'%3E%3Cg id='Group-Copy' transform='translate(0.000000, 164.000000)'%3E%3Cg id='ic-check-18px' transform='translate(25.000000, 23.000000)'%3E%3Cpolygon id='check' points='6.61 11.89 3.5 8.78 2.44 9.84 6.61 14 15.56 5.05 14.5 4'%3E%3C/polygon%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        background-size: 14px 10px;
    }
    .checkbox-inner {
        @apply flex justify-center items-center border border-primary-light-jacket-50 size-7 text-transparent;
        background: transparent no-repeat center;
    }
</style>
