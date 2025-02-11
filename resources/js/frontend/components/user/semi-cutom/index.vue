<script setup>
    import { defineAsyncComponent, ref, watch, computed, onMounted } from 'vue';
    import { usePage } from '../../../store/page';
    import { useCustomer } from '../../../store/customer';
    import { useProducts } from '../../../store/product';
    import axios from 'axios';
    import { isEmpty } from 'lodash';


    const storePage = usePage();
    const storeCustomer = useCustomer();
    const storeProducts = useProducts();

    const props = defineProps({
        csrf: String,
        user: Object,
        route_edit_profile: String,
        route_logout: String,
        data_custom_shirt: Object,
        data_custom_request: Object,
        data_semi_custom: Object,
        api_store_order: String,
        api_customer_size: String,

    });

    const Layout = defineAsyncComponent(() => import('../../includes/Layout.vue'));

    const CustomBasic = defineAsyncComponent(() => import('./includes/CustomBasic.vue'));
    const CustomOptions = defineAsyncComponent(() => import('./includes/CustomOptions.vue'));

    const Customer = defineAsyncComponent(() => import('../includes/CustomerData.vue'));
    const TotalShop = defineAsyncComponent(() => import('../includes/TotalShop.vue'));
    const Payment = defineAsyncComponent(() => import('../includes/Payment.vue'));

    const currentSection = ref(null);

    const urlParams = new URLSearchParams(window.location.search);
    const url = new URL(window.location.href);

    const extend = computed(() => {
        return storePage.get == 'semi-custom' ? true : false
    })

    const childBasic = ref(null);
    const childOption = ref(null);

    const formBasic = computed(() => {
        return childBasic.value?.form;
    });

    const formActual =  computed(() => {
        return childBasic.value?.formSize;
    });

    const basic = computed(() => {
        let data = {
            form: childBasic.value?.form,
            amount: childBasic.value?.amount,
            additionalNote: childBasic.value?.additionalNote,
            formSize: childBasic.value?.formSize
        };
        return data;
    });

    const option = computed(() => {
        let data = {
            form: childOption.value?.form,
            amount: childOption.value?.amount,
            additionalNote: childOption.value?.additionalNote
        };
        return data;
    });

    const formOptions = computed(() => {
        return childOption.value?.form;
    });

    const amount = ref({
        basic: {},
        option: {}
    });

    const totalPrice = ref(0);

    // form for send to backend
    const bindForm = ref(null);

    onMounted(() => {
        bindForm.value = {
            basic: basic,
            option: option,
        }

        if (urlParams.get('page') != null) {
            storePage.currentPage = urlParams.get('page');
        }else{
            storePage.currentPage = currentSection.value;
            storeCustomer.customer = null;
            storeProducts.resetSemiCustom();
        }
    });

    watch(amount.value, (items) => {
        let basic = parseInt(items.basic?.total ?? 0);
        let option = parseInt(items.option?.total ?? 0);
        let giftCard = parseInt(items.option?.giftCard ?? 0);

        // totalPrice.value = basic + option;
        totalPrice.value = basic + option - giftCard;

        childBasic.value.amount = amount.value.basic;
        childOption.value.amount = amount.value.option;
        bindForm.value.totalPrice = totalPrice.value;
    });

    const additionalBasic = (price) => {
        amount.value.basic = price;
    };

    const additionalOption = (price) => {
        amount.value.option = price;
    };

    const stingConvert = (text) => {
        const string = text.replace(/([A-Z])/g, " $1");
        return string.charAt(0).toUpperCase() + string.slice(1);
    };

    const currencyFormat = (value) => {
        if (!value){
            return 0
        }
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(
            value,
        )
    };

    const btnNext = (section, data) => {
        url.searchParams.set('page', section);
        url.searchParams.set('index', 0);
        window.history.pushState(null, '', url.toString());
        storePage.currentPage = section;
    }


    const btnSubmit =  async () => {
        childBasic.value.basicAmount();
        childOption.value.amountOption();

        if (basic.value.form.fabric.fabricCode == null || basic.value.form.fabric.text == null ||
            basic.value.form.fabric.fabricCode == '' || basic.value.form.fabric.text == ''
        ) {
            alert('Please fill the form Fabric Code');
        }else {
            if (totalPrice.value <= 0 && bindForm.value !== null) {
                alert('Please fill the form OR apply the price first');
            }else {
                useProducts().resetDuplicateSm();
                useProducts().setCustom(bindForm.value);
                window.location.href = "/cart";
            }
        }
    }

    const duplicateSemiCustom = () => {
        childBasic.value.basicAmount();
        childOption.value.amountOption();

        if (basic.value.form.fabric.fabricCode == null || basic.value.form.fabric.text == null ||
            basic.value.form.fabric.fabricCode == '' || basic.value.form.fabric.text == ''
        ) {
            alert('Please fill the form Fabric Code');
        }else {
            if (totalPrice.value <= 0 && bindForm.value !== null) {
                alert('Please fill the form OR apply the price first');
            }else {
                    let urlParams = new URLSearchParams(window.location.search);
                    let index = urlParams.get('index');
                    let nextIndex = parseInt(index);

                    storeProducts.setIndexSemiCustom(nextIndex);
                    useProducts().setCustom(bindForm.value);

                    //  set duplicate
                    useProducts().setDuplicateSm(bindForm.value);

                    // useProducts().setCustomWithKey(bindForm.value, nextIndex);

                    setTimeout(() => {
                        window.location.href = `/semi-custom?page=semi-custom&index=${nextIndex + 1}`;
                    });
                    alert('Success');
                }
            }
    }

    const goToCart = () => {
        window.location.href = "/cart";
    }

    const addCustomRequest = () => {
        let urlParams = new URLSearchParams(window.location.search);
        let index = urlParams.get('index');

        childBasic.value.basicAmount();
        childOption.value.amountOption();

        if (basic.value.form.fabric.fabricCode == null || basic.value.form.fabric.text == null ||
            basic.value.form.fabric.fabricCode == '' || basic.value.form.fabric.text == ''
        ) {
            alert('Please fill the form Fabric Code');
        }else {
            if (totalPrice.value <= 0 && bindForm.value !== null) {
                alert('Please fill the form OR apply the price first');
            }else {
                useProducts().resetDuplicateSm();
                useProducts().setCustom(bindForm.value);
                // set time out
                setTimeout(() => {
                    window.location.href = `/semi-custom?page=semi-custom&index=${(parseInt(index) + 1)}`;
                });
                alert('Success');
            }
        }
    }

    const hasDuplicate = computed(() => {
        return totalPrice.value > 0 ? true : false;
    });

    const hasSemiCustom = computed(() => {
        return storeProducts.semi_custom.length > 0 ? true : false;
    });

</script>

<template>
    <Layout :route_edit_profile="route_edit_profile" :route_logout="route_logout" :user="user" :csrf="csrf" :extends="extend">
        <template #sidebar>
            <div :class="{'hidden': !extend}" class="sticky top-0 h-screen overflow-y-auto bg-green scroll-box">
                <div class="py-20 bg-primary-50">
                    <div class="mx-[5%] xl:mx-[10%] 2xl:mx-[20%] font-roboto text-white">
                        <div>
                            <div class="text-2xl tracking-widest text-center uppercase font-josefin xl:text-4xl">ORDER SUMMARY</div>
                            <div class="bg-white opacity-70 mx-auto my-10 w-4/6 h-0.5"></div>
                            <div>
                                <table>
                                    <tbody class="*:space-y-4">
                                        <tr class="*:align-top max-xl:text-sm lg:whitespace-pre-wrap"
                                            v-for="(summaryBasic, key) in formBasic" :key="key">
                                            <td class="capitalize">{{ stingConvert(key) }}</td>
                                            <td class="w-4 text-center">:</td>
                                            <td v-if="key == 'fabric'">
                                                <div>{{ summaryBasic?.fabricCode ?? 'none' }}</div>
                                                <div>{{ summaryBasic?.text ?? '' }}</div>
                                            </td>
                                            <td v-else>{{ (summaryBasic?.name ?? (summaryBasic?.optionNumber) ) ?? 'none' }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table class="mt-10">
                                    <tbody>
                                        <tr>
                                            <td class="font-bold uppercase">Option:</td>
                                        </tr>
                                        <tr class="*:align-top max-xl:text-sm lg:whitespace-pre-wrap"
                                            v-for="(summaryOption, key) in formOptions" :key="key">
                                            <td class="capitalize">{{ stingConvert(key) }} </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">
                                                <div v-if="key == 'tape'">
                                                    <div>{{ summaryOption?.collar ?? 'none' }}</div>
                                                    <div>{{ summaryOption?.lower }}</div>
                                                </div>
                                                <div v-else-if="key == 'embroidery'">
                                                    <div v-if="isEmpty(summaryOption)">none</div>
                                                    <div v-if="summaryOption.position">
                                                        {{ summaryOption.position.name }}
                                                    </div>
                                                    <div v-if="summaryOption.color">
                                                        {{ summaryOption.color.name }}
                                                    </div>
                                                    <div v-if="summaryOption.fontType">
                                                        {{ summaryOption.fontType.name }}
                                                    </div>
                                                    <div v-if="summaryOption.initialName">
                                                        {{ summaryOption.initialName?.x }}
                                                        {{ summaryOption.initialName?.y }}
                                                        {{ summaryOption.initialName?.dot }}
                                                        {{ summaryOption.initialName?.z }}
                                                    </div>
                                                    <div v-if="summaryOption.longName">
                                                        {{ summaryOption.longName }}
                                                    </div>
                                                </div>
                                                <div v-else>
                                                    {{ (summaryOption?.name ?? summaryOption?.fabricCode) ?? 'none' }}
                                                </div>
                                                <div v-for="option in summaryOption?.data">
                                                    - {{ option.name }}
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mt-20">
                            <div class="text-2xl tracking-widest text-center uppercase font-josefin xl:text-4xl">ACTUAL</div>
                            <div class="bg-white opacity-70 mx-auto my-10 w-4/6 h-0.5"></div>
                            <div>
                                <table>
                                    <tbody class="*:space-y-4">
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Order </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">{{ formActual?.order }}</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Body Type </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">{{ formActual?.bodyType }}</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Sleeve </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">{{ formActual?.sleeve }}</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Shirt’s Neck</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">{{ formActual?.shirt.neck }}</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Shirt’s Right Sleeve</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">{{ formActual?.shirt.rightSleeve }}</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Shirt’s Left Sleeve</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">{{ formActual?.shirt.leftSleeve }}</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Shirt’s Chest </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">{{ formActual?.shirt.chest }}</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Shirt’s Waist </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">{{ formActual?.shirt.waist }}</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Shirt’s Shoulder </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">{{ formActual?.shirt.shoulder }}</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Actual’s Neck</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">{{ formActual?.actual.neck }}</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Actual’s Right Sleeve  </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">{{ formActual?.actual.rightSleeve }}</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Actual’s Left Sleeve </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">{{ formActual?.actual.leftSleeve }}</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Actual’s Chest </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">{{ formActual?.actual.chest }}</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Actual’s Waist </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">{{ formActual?.actual.waist }}</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Actual’s Shoulder </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">{{ formActual?.actual.shoulder }}</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>SA Neck Size  </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">{{ formActual?.sa.neckSize }}</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>SA Shoulder  </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">{{ formActual?.sa.shoulder }}</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>SA Back Length  </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">{{ formActual?.sa.backLength }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-white font-roboto">
                    <div class="bg-secondary-50 px-[5%] xl:px-[10%] 2xl:px-[20%] py-8">
                        <div>
                            <table>
                                <tbody class="*:space-y-4 font-bold">
                                    <tr class="lg:whitespace-nowrap">
                                        <td>Base Price</td>
                                        <td class="text-center">:</td>
                                        <td class="">{{ currencyFormat(amount?.basic?.price) ?? '0' }}</td>
                                    </tr>
                                    <tr class="lg:whitespace-nowrap">
                                        <td>Discount</td>
                                        <td class="text-center">:</td>
                                        <td class="">{{ amount?.basic?.discount ?? '0' }}%</td>
                                    </tr>
                                    <tr class="lg:whitespace-nowrap">
                                        <td>Option</td>
                                        <td class="text-center">:</td>
                                        <td class="">{{ currencyFormat(amount?.option?.total) ?? '0' }}</td>
                                    </tr>
                                    <tr class="lg:whitespace-nowrap" v-if="amount?.option?.giftCard > 0">
                                        <td>Gift Card</td>
                                        <td class="text-center">:</td>
                                        <td class="">- {{ currencyFormat(amount?.option?.giftCard) ?? '0' }}</td>
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
                    <div class="pt-3 pb-2 font-bold tracking-widest text-center uppercase bg-black font-josefin lg:text-lg xl:text-xl 2xl:text-3xl">
                        IDR {{ currencyFormat(totalPrice) ?? '0' }},-
                    </div>
                </div>
            </div>
        </template>

        <template v-if="storePage.get == 'customer-data' || storeCustomer.getCustomer == null">
            <Customer onPage="semi-custom" @btn-next="btnNext"/>
        </template>

        <template v-if="storePage.get == 'semi-custom' && storeCustomer.getCustomer != null">

            <CustomBasic @additionalBasic="additionalBasic" :dataSemiCustom="props.data_semi_custom" ref="childBasic" :api_customer_size="props.api_customer_size" />

            <CustomOptions @additionalOption="additionalOption" :dataOptions="props.data_semi_custom" ref="childOption"/>

            <div class="absolute bottom-0 right-0">
                <div class="flex items-end">
                    <button v-if="hasSemiCustom" @click="goToCart" class="flex items-center gap-2 p-6 tracking-widest text-white bg-secondary-50 h-fit">
                        <span>CANCEL & SUBMIT</span>
                        <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
                    </button>

                    <div>
                        <button v-if="hasDuplicate" @click="duplicateSemiCustom()" class="flex items-center justify-between w-full gap-2 p-6 tracking-widest text-white bg-primary-50">
                            <span>DUPLICATE</span>
                            <img class="inline-block rotate-90" src="img/icons/arrw-ck-right.png" alt="">
                        </button>
                        <button @click="addCustomRequest()" class="flex items-center gap-2 p-6 tracking-widest text-white bg-primary-300 h-fit">
                            <span>ADD NEW CUSTOM REQUEST </span>
                            <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
                        </button>
                    </div>

                    <button @click="btnSubmit()" class="flex items-center gap-2 p-6 tracking-widest text-white bg-secondary-50 h-fit">
                        <span>SUBMIT</span>
                        <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
                    </button>

                </div>
            </div>
        </template>

    </Layout>
</template>
