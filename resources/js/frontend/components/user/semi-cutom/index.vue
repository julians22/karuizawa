<script setup>
    import { defineAsyncComponent, ref, watch, computed, onMounted } from 'vue';

    const props = defineProps({
        csrf: String,
        user: Object,
        route_edit_profile: String,
        route_logout: String,
        data_custom_shirt: Object,
        data_custom_request: Object,
        data_semi_custom: Object
    });

    const Layout = defineAsyncComponent(() => import('../../includes/Layout.vue'));
    const CustomShirt = defineAsyncComponent(() => import('./includes/CustomShirt.vue'));
    const CustomRequest = defineAsyncComponent(() => import('./includes/CustomRequest.vue'));

    const childBasic = ref(null);
    const childOption = ref(null);

    const formBasic = computed(() => {
        return childBasic.value?.form;
    });

    const basic = computed(() => {
        let data = {
            form: childBasic.value?.form,
            amount: childBasic.value?.amount,
            additionalNote: childBasic.value?.additionalNote
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
    })

    watch(amount.value, (items) => {
        let basic = parseInt(items.basic?.total ?? 0);
        let option = parseInt(items.option?.total ?? 0);

        totalPrice.value = basic + option;

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
            return ''
        }
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(
            value,
        )
    };

    const btnSubmit = () => {
        childBasic.value.basicAmount();
        childOption.value.amountOption();
    }
</script>

<template>
    <Layout :route_edit_profile="route_edit_profile" :route_logout="route_logout" :user="user" :csrf="csrf" :extends="true">
        <template #sidebar>
            <div class="sticky top-0 h-screen overflow-y-auto scroll-box bg-green">
                <div class="py-20 bg-primary-50">
                    <div class="mx-[5%] xl:mx-[10%] 2xl:mx-[20%] font-roboto text-white">
                        <div>
                            <div class="text-2xl tracking-widest text-center uppercase xl:text-4xl font-josefin">ORDER SUMMARY</div>
                            <div class="w-4/6 h-0.5 bg-white mx-auto opacity-70 my-10"></div>
                            <div>
                                <table>
                                    <tbody class="*:space-y-4">
                                        <tr class="lg:whitespace-pre-wrap *:align-top"
                                            v-for="(summaryBasic, key) in formBasic" :key="key">
                                            <td class="capitalize">{{ stingConvert(key) }}</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">{{ (summaryBasic?.name ?? summaryBasic?.fabricCode) ?? 'none' }}</td>
                                        </tr>
                                        <!-- <tr class="lg:whitespace-pre-wrap">
                                            <td>Collar</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">{{ formCustom?.collar.name }}</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Sleeve</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">Short Sleeve</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Body Type</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">
                                                <div>Front - 2. French Palette</div>
                                                <div>Back - 4. No Pleats</div>
                                                <div>Hem - Standard Hem</div>
                                            </td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Pocket </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">Round</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Button</td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">
                                                <div>Button - Purple </div>
                                                <div>Type - None</div>
                                                <div>Fabric - None </div>
                                            </td>
                                        </tr> -->
                                    </tbody>
                                </table>

                                <table class="mt-10">
                                    <tbody>
                                        <tr>
                                            <td class="font-bold uppercase">Option:</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap *:align-top"
                                            v-for="(summaryBasic, key) in formOptions" :key="key">
                                            <td class="capitalize">{{ stingConvert(key) }} </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">
                                                <div v-if="key == 'tape'">
                                                    <div>{{ summaryBasic?.collar ?? 'none' }}</div>
                                                    <div>{{ summaryBasic?.lower }}</div>
                                                </div>
                                                <div v-else>
                                                    {{ (summaryBasic?.name ?? summaryBasic?.fabricCode) ?? 'none' }}
                                                </div>
                                                <div v-for="option in summaryBasic?.data">
                                                    - {{ option.name }}
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mt-20">
                            <div class="text-2xl tracking-widest text-center uppercase xl:text-4xl font-josefin">ACTUAL</div>
                            <div class="w-4/6 h-0.5 bg-white mx-auto opacity-70 my-10"></div>
                            <div>
                                <table>
                                    <tbody class="*:space-y-4">
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Order </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">New Order</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Body Type </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">2. Slim</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Sleeve </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">Regular Sleeve</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Neck </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">43</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Shirt’s Right Sleeve  </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">92</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Shirt’s Left Sleeve </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">89</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Shirt’s Chest </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">43</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Shirt’s Waist </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">92</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Shirt’s Shoulder </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">89</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Actual’s Right Sleeve  </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">43</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Actual’s Left Sleeve </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">43</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Actual’s Chest </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">43</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Actual’s Waist </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">43</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>Actual’s Shoulder </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">93</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>SA Neck Size  </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">-</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>SA Shoulder  </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">-</td>
                                        </tr>
                                        <tr class="lg:whitespace-pre-wrap">
                                            <td>SA Back Length  </td>
                                            <td class="w-4 text-center">:</td>
                                            <td class="">-</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-white font-roboto">
                    <div class="py-8 bg-secondary-50 px-[5%] xl:px-[10%] 2xl:px-[20%]">
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
                                    <tr class="lg:whitespace-nowrap">
                                        <td>Total Price</td>
                                        <td class="w-full text-center">:</td>
                                        <td class="">{{ currencyFormat(totalPrice) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="pt-3 pb-2 font-bold tracking-widest text-center uppercase bg-black lg:text-lg xl:text-xl 2xl:text-3xl font-josefin">
                        IDR {{ currencyFormat(totalPrice) ?? '0' }},-
                    </div>
                </div>
            </div>
        </template>

        <!-- <template v-if="currentSection == 'custom-shirt'"> -->
            <CustomShirt @additionalBasic="additionalBasic" :dataSemiCustom="props.data_semi_custom" ref="childBasic" />
        <!-- </template> -->

        <!-- <template v-if="currentSection == 'custom-request'"> -->
            <CustomRequest @additionalOption="additionalOption" :dataOptions="props.data_semi_custom" ref="childOption"/>
        <!-- </template> -->

        <div class="absolute bottom-0 right-0 flex">
            <button class="flex items-center gap-2 p-6 tracking-widest text-white bg-primary-300">
                <span>ADD CUSTOM REQUEST </span>
                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
            </button>
            <button @click="btnSubmit()" class="flex items-center gap-2 p-6 tracking-widest text-white bg-secondary-50">
                <span>SUBMIT</span>
                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
            </button>
        </div>
    </Layout>
</template>
