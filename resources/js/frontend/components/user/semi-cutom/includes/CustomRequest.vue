<script setup>
    import { isNull } from 'lodash';
    import { ref, defineProps, watch, defineExpose, computed } from 'vue'
    // import boxInput from '../../../utils/fields/InputBox.vue';
    import  InputBox  from '@frontend/components/utils/fields/InputBox.vue';
    import { useProducts } from '../../../../store/product';

    import { component as VueNumber } from '@coders-tm/vue-number-format';

    const number_input = {
        separator: '.',
        prefix: 'Rp ',
        precision: 0,
        masked: false,
    }


    const props = defineProps({
        dataOptions: Object
    });

    const emitFrom = defineEmits({
        'additional-option': 'additional-option',
    });

    const form = ref({
        collar: {},
        cuffs: {},
        frontody: {},
        button: {},
        bodySnapButton: {},
        cleric: {},
        buttonCode: {},
        buttonHole: {},
        buttonThread: {},
        stitchThread: {},
        embroidery: {},
        interlining: {},
        sewingOption: {},
        tape: {},
    });

    const mainCleric = ref(null);
    const selectCleric = ref([]);

    const onSelectCleric = () => {
        form.value.cleric.data = selectCleric.value;
    }

    const onChangeCleric = () => {
        form.value.cleric = {
            name: mainCleric.value.name,
            slug: mainCleric.value.slug,
            price: mainCleric.value.price
        }
        selectCleric.value = [];
    };

    const additionalNote = ref(null);

    const amount = ref({
        optionPrice: 0,
        basicPrice: 0,
        // discount: 0,
        giftCard: 0,
        total: 0
    });

    const price = ref(0);
    const discount = ref(null);
    const dataPrice = ref(null);
    const giftCard = ref(0);

    const amountOption = () => {
        let x = parseInt(dataPrice.value?.optionTotal) ? parseInt(dataPrice.value?.optionTotal) : 0;
        let y = parseInt(price?.value) ? parseInt(price?.value) : 0;
        // let disc = parseInt(discount?.value) ? parseInt(discount?.value) : 0;
        let gift_card = parseInt(giftCard.value) ? parseInt(giftCard.value) : 0;
        let z = x + y;
        // let total = z - (z * disc / 100);

        amount.value.optionPrice = x;
        // amount.value.discount = disc;
        amount.value.giftCard = gift_card;
        amount.value.selected_price = x;
        amount.value.price = y;
        amount.value.total = z;
        // amount.value.total = total;

        emitFrom('additional-option', amount);
    };

    // const initialName = ref({
    //     x: '',
    //     y: '',
    //     dot: '',
    //     z: ''
    // });

    // watch(initialName.value, (items) => {
    //     embroidery.value.initialName = initialName.value
    // })

    const embroidery = ref({
        slug: 'embroidery',
        position: null,
        color: null,
        fontType: null,
        initialName: {
            x: '',
            y: '',
            dot: '',
            z: ''
        },
        longName: null,
        price: 0
    });

    watch(embroidery.value, (items) => {
        if (
            (
                isNull(items.longName) ||
                items.longName == ''
            ) &&
            (
                items.initialName.x == '' &&
                items.initialName.y == '' &&
                items.initialName.dot == '' &&
                items.initialName.z == ''
            ) &&
            (
                isNull(items.position) ||
                items.position == ''
            ) &&
            (
                isNull(items.color) ||
                items.color == ''
            ) &&
            (
                isNull(items.fontType) ||
                items.fontType == ''
            )
        ) {
            items.price = 0;
        }else {
            items.price = 50000;
        }

        form.value.embroidery = items;
    });

    const tape = ref({
        slug: 'tape',
        collar: null,
        lower: null,
        price: 200000,
    });

    watch(tape.value, (items) => {
        if (isNull(tape.value.collar) || tape.value.collar == '' && isNull(tape.value.lower) || tape.value.lower == '') {
            form.value.tape = null;
            return;
        }
        form.value.tape = tape.value;
    });

    watch(form.value, (items) => {
        const groupedData = groupAndCountByPrice(items);
        dataPrice.value = groupedData;

        let radio = document.querySelectorAll("input[type=radio]:checked");

        // unchecked radio
        for(let i = 0; i < radio.length; i++) {
            radio[i].onclick = function(e) {
                let myVariable = e.target.name;

                if (myVariable == 'cleric') {
                    mainCleric.value = null;
                    selectCleric.value = null;
                }


                let camelCased = myVariable.replace(/-([a-z])/g, function (g) { return g[1].toUpperCase(); });

                if (camelCased !== 'embroideryPosition' || camelCased !== 'embroideryColor' || camelCased !== 'embroideryFontType' || camelCased !== 'embroideryInitialName' || camelCased !== 'embroideryLongName') {
                    // find form value key
                    const key = Object.keys(form.value).find(key => key === camelCased);

                    form.value[key] = null;
                }


            }
        }

    });

    const groupAndCountByPrice = (data) => {
        const result = {
            grouped: {},
            optionTotal: 0,
        };

        for (const key in data) {
            const item = data[key];

            if (!item || item.price == null) {
                continue;
            }

            const { price } = data[key];

            if (!result.grouped[price]) {
                result.grouped[price] = { quantity: 1, subTotal: price };
            } else {
                result.grouped[price].quantity++;
                result.grouped[price].subTotal += price;
            }

            result.optionTotal += price;
        }

        return {
            ...result.grouped,
            optionTotal: result.optionTotal,
        };
    };


    const currencyFormat = (value) => {
        if (!value){
            return ''
        }
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(
            value,
        )
    };

    function onInputBox(val, key = 'fabric', key2 = 'fabricCode')
    {
        form.value[key][key2] = val;
    }

    const onInputIntialName = (val, key = 'z') => {
        embroidery.value.initialName[key] = val;

    }
    const onInputTape = (val, key = 'collar') => {
        tape.value[key] = val;
    }

    defineExpose({
        form,
        additionalNote,
        amount,
        amountOption
    });
</script>

<template>
    <div class="relative mb-28 min-h-svh">
        <!-- collar -->
        <div id="collar">
            <div class="flex items-center justify-between py-2 bg-blue-ka px-14">
                <div class="text-xl font-bold tracking-wider text-white uppercase">02. Collar</div>
            </div>

            <div class="grid grid-cols-6 gap-2 my-10 px-14 whitespace-nowrap">
                <div class="col-span-6 pt-2 xl:col-span-2">
                    <div class="flex" v-for="(collar, index) in dataOptions.collar.data.options.option_1" :key="index">
                        <input class="hidden"v-model="form.collar" :value="collar" type="radio" name="collar" :id="'collar-'+collar.slug">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="'collar-'+collar.slug">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">{{ collar.name }}</div>
                        </label>
                    </div>
                    <div class="mt-2 mb-3 text-xs font-roboto text-pink-ka">*No Additional Charge for above items</div>
                </div>
                <div class="col-span-6 xl:col-span-4">
                    <div class="grid grid-cols-1 p-2 border border-pink-ka 2xl:grid-cols-2">
                        <div class="flex" v-for="(collar50, index) in dataOptions.collar.data.options.option_2" :key="index">
                            <input class="hidden" type="radio" name="collar" v-model="form.collar" :value="collar50" :id="'collar-'+collar50.slug">
                            <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="'collar-'+collar50.slug">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">{{ collar50.name }}</div>
                            </label>
                        </div>
                        <div class="self-end col-span-2 justify-items-end">
                            <div class="">
                                <input class="hidden" type="radio" name="collar-50" :id="`50rb`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`50rb`">
                                    <div class="text-xs font-bold tracking-wider text-center uppercase text-pink-ka xl:text-sm">
                                        {{ currencyFormat(dataOptions.collar.data.options.option_2[0].price) }}
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 p-2 border border-pink-ka">
                        <div class="col-span-3">
                            <div class="flex" v-for="(collar100, index) in dataOptions.collar.data.options.option_3" :key="index">
                                <input class="hidden" v-model="form.collar" :value="collar100" type="radio" name="collar" :id="`collar-${collar100.slug}`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`collar-${collar100.slug}`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">{{ collar100.name }}</div>
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center col-span-2 mt-4 max-xl:flex-wrap lg:gap-4 fabric-code">
                            <label for="fabric-code" class="tracking-wider uppercase text-primary-50 whitespace-nowrap">fabric code (4 digit)</label>
                            <div class="flex font-roboto">
                                <InputBox :digitCount="4" @update:input="onInputBox($event, 'collar', 'fabricCode')"/>
                            </div>
                        </div>
                        <div class="self-end justify-items-end col-3">
                            <div class="">
                                <input class="hidden" type="radio" name="collar-100" :id="`100rb`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`100rb`">
                                    <div class="text-xs font-bold tracking-wider text-center uppercase text-pink-ka xl:text-sm">
                                        {{ currencyFormat(dataOptions.collar.data.options.option_3[0].price) }}
                                    </div>
                                    <!-- <span class="checkbox-inner pink"></span> -->
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- cuffs -->
        <div id="cuffs">
            <div class="flex items-center justify-between py-2 bg-blue-ka px-14">
                <div class="text-xl font-bold tracking-wider text-white uppercase">03. cuffs</div>
            </div>

            <div class="grid grid-cols-6 gap-2 my-10 px-14 whitespace-nowrap">
                <div class="col-span-6 pt-2 xl:col-span-2">
                    <div class="flex" v-for="(cuffs, index) in dataOptions.cuffs.data.options.option_1" :key="index">
                        <input class="hidden" type="radio" name="cuffs" v-model="form.cuffs" :value="cuffs" :id="`cuffs-${cuffs.slug}`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cuffs-${cuffs.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">{{ cuffs.name }}</div>
                        </label>
                    </div>
                    <div class="mt-4 text-xs font-roboto text-pink-ka">*No Additional Charge for above items</div>
                </div>
                <div class="col-span-6 xl:col-span-4">
                    <div>
                        <div class="grid grid-cols-1 gap-4 p-2 border border-pink-ka 2xl:grid-cols-2">
                            <div class="flex" v-for="(cuffs, index) in dataOptions.cuffs.data.options.option_2" :key="index">
                                <input class="hidden" v-model="form.cuffs" :value="cuffs" type="radio" name="cuffs" :id="`cuffs-${cuffs.slug}`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cuffs-${cuffs.slug}`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">{{ cuffs.name }}</div>
                                </label>
                            </div>
                            <div class="self-end justify-items-end col-3">
                                <div class="">
                                    <input class="hidden" type="radio" name="cuffs-70" :id="`cuffs-70rb`">
                                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cuffs-70rb`">
                                        <div class="text-xs font-bold tracking-wider text-center uppercase text-pink-ka xl:text-sm">
                                            {{ currencyFormat(dataOptions.cuffs.data.options.option_2[0].price) }}
                                        </div>
                                        <!-- <span class="checkbox-inner pink"></span> -->
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 p-2 border border-pink-ka">
                            <div class="col-span-3">
                                <div class="flex" v-for="(cuffs, index) in dataOptions.cuffs.data.options.option_3" :key="index">
                                    <input class="hidden" v-model="form.cuffs" :value="cuffs" type="radio" name="cuffs" :id="`cuffs-${cuffs.slug}`">
                                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cuffs-${cuffs.slug}`">
                                        <span class="checkbox-inner"></span>
                                        <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">{{ cuffs.name }}</div>
                                    </label>
                                </div>
                            </div>
                            <div class="flex items-center col-span-2 mt-4 max-lg:flex-wrap lg:gap-8 fabric-code">
                                <label for="fabric-code" class="tracking-wider uppercase text-primary-50 whitespace-nowrap">fabric code (4 digit)</label>
                                <div class="flex font-roboto">
                                    <InputBox :digitCount="4" @update:input="onInputBox($event, 'cuffs', 'fabricCode')"/>
                                </div>
                            </div>
                            <div class="self-end justify-items-end cols-3">
                                <div class="">
                                    <input class="hidden" type="radio" name="cuffs-100" :id="`cuffs-100rb`">
                                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cuffs-100rb`">
                                        <div class="text-xs font-bold tracking-wider text-center uppercase text-pink-ka xl:text-sm">
                                            {{ currencyFormat(dataOptions.cuffs.data.options.option_3[0].price) }}
                                        </div>
                                        <!-- <span class="checkbox-inner pink"></span> -->
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Front Body -->
                    <div class="my-6">
                        <div class="flex items-center justify-between py-2 bg-blue-ka px-14">
                            <div class="text-xl font-bold tracking-wider text-white uppercase">04. FRONT BODY</div>
                        </div>
                        <div class="flex justify-between p-2 mt-4 border border-pink-ka">
                            <div class="flex" v-for="(frontBody, index) in dataOptions.front_body.data.options.option_1" :key="index">
                                <input class="hidden" type="radio" name="front-body" :value="frontBody" v-model="form.frontBody"  :id="`hidden-placket`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`hidden-placket`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">{{ frontBody.name }}</div>
                                </label>
                            </div>
                            <div class="">
                                <input class="hidden" type="radio" name="front-bdy-100" :id="`front-bdy-100`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`front-bdy-100`">
                                    <div class="text-xs font-bold tracking-wider text-center uppercase text-pink-ka xl:text-sm">
                                        {{ currencyFormat(dataOptions.front_body.data.options.option_1[0].price) }}
                                    </div>
                                    <!-- <span class="checkbox-inner pink"></span> -->
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- button & body snap button -->
        <div class="xl:flex gap-4 *:w-full">
            <div class="">
                <div class="flex items-center justify-between py-2 bg-blue-ka px-14">
                    <div class="text-xl font-bold tracking-wider text-white uppercase">08. BUTTON</div>
                </div>
                <div class="flex justify-between p-2 mt-4 border border-pink-ka max-xl:mx-6 max-xl:mb-4 ml-14">
                    <div v-for="(button, index) in dataOptions.button.data.options.option_1" :key="index">
                        <input class="hidden" type="radio" :value="button" v-model="form.button" name="button" :id="`btn-${button.slug}`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`btn-${button.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">{{ button.name }}</div>
                        </label>
                    </div>
                    <div class="flex font-roboto">
                        <InputBox :digitCount="4" @update:input="onInputBox($event, 'button', 'buttonCode')"/>
                    </div>
                    <div class="">
                        <input class="hidden" type="radio" name="button-100" :id="`button-100`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`button-100`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase text-pink-ka xl:text-sm">
                                {{ currencyFormat(dataOptions.button.data.options.option_1[0].price) }}
                            </div>
                            <!-- <span class="checkbox-inner pink"></span> -->
                        </label>
                    </div>
                </div>
            </div>
            <div class="">
                <div class="flex items-center justify-between py-2 bg-blue-ka px-14">
                    <div class="text-xl font-bold tracking-wider text-white uppercase">09. BODY SNAP BUTTON</div>
                </div>
                <div class="flex justify-between p-2 mt-4 border border-pink-ka max-xl:mx-6 mr-14">
                    <div v-for="bodySnapButton in dataOptions.body_snap_button.data.options.option_1" :key="bodySnapButton">
                        <input class="hidden" :value="bodySnapButton" v-model="form.bodySnapButton" type="radio" name="body-snap-button" :id="`${bodySnapButton.slug}`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`${bodySnapButton.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">{{ bodySnapButton.name }}</div>
                        </label>
                    </div>
                    <div class="">
                        <input class="hidden" type="radio" name="bdy-snap-100" :id="`bdy-snap-50`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`bdy-snap-50`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase text-pink-ka xl:text-sm">
                                {{ currencyFormat(dataOptions.body_snap_button.data.options.option_1[0].price) }}
                            </div>
                            <!-- <span class="checkbox-inner pink"></span> -->
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- cleric -->
         <div class="mt-4">
             <div class="flex items-center justify-between py-2 bg-blue-ka px-14">
                 <div class="text-xl font-bold tracking-wider text-white uppercase">10. Cleric</div>
             </div>

             <div v-for="(dataCleric, index) in dataOptions.cleric.data.options" :key="index"
                class="p-4 mx-6 mt-4 space-y-4 border border-pink-ka xl:mx-14">
                <div v-for="(subCeleric, index) in dataCleric" :key="index" class="flex gap-2">
                    <div>
                        <input v-model="mainCleric" @change="onChangeCleric" class="hidden" type="radio" name="cleric"  :value="subCeleric" :id="`${subCeleric.slug}`">
                        <label class="grid items-center h-full grid-cols-2 gap-1 rounded cursor-pointer" :for="`${subCeleric.slug}`">
                            <div class="text-sm font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">{{ subCeleric.no }}</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="flex gap-2 checkbox-cleric" :class="{ 'pointer-events-none': mainCleric?.slug != subCeleric.slug}">
                        <div v-for="clericItems in subCeleric.data">
                            <input @change="onSelectCleric" class="hidden" type="checkbox" name="clericItems" v-model="selectCleric" :value="clericItems" :id="`cleric-${subCeleric.no}-${clericItems.slug}`">
                            <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`cleric-${subCeleric.no}-${clericItems.slug}`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">{{ clericItems.name }}</div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex self-end gap-2 justify-self-end">
                    <div class="flex flex-col col-span-2 gap-1 mt-4 fabric-code">
                            <label for="fabric-code" class="tracking-wider uppercase text-primary-50 whitespace-nowrap">fabric code (4 digit)</label>
                            <div class="flex font-roboto">
                                <InputBox :digitCount="4" @update:input="onInputBox($event, 'cleric', 'fabricCode')"/>
                            </div>
                        </div>
                    <div class="self-end">
                        <input class="hidden" type="radio" name="cleric-50rb" :id="`cleric-50rb`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cleric-50rb`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase text-pink-ka xl:text-sm">{{ currencyFormat(dataCleric[0].price) }}</div>
                        </label>
                    </div>
                </div>
            </div>
         </div>

         <!-- button hole, button thread, stitch thread -->
         <div class="grid gap-2 mt-4 lg:grid-cols-2 xl:grid-cols-3 whitespace-nowrap">
            <!-- button hole -->
            <div class="max-lg:col-span-2">
                <div class="flex items-center justify-between py-2 bg-blue-ka px-14">
                    <div class="text-lg font-bold tracking-wider text-white uppercase">11. button hole</div>
                </div>

                <div class="grid grid-cols-2 gap-2 p-4 mx-6 mt-4 border border-pink-ka lg:mr-0 lg:ml-6 xl:ml-14">
                    <div class="flex" v-for="(buttonHole, index) in dataOptions.button_hole.data.options.option_1" :key="index">
                        <input class="hidden" type="radio" name="button-hole" :value="buttonHole" v-model="form.buttonHole"  :id="`botton-hole-${buttonHole.slug}`">
                        <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`botton-hole-${buttonHole.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">{{ index + 1 }}. {{ buttonHole.name }}</div>
                        </label>
                    </div>

                    <div class="self-end col-span-2 justify-self-end">
                        <input class="hidden" type="radio" name="button-hole-30rb" :id="`button-hole-30rb`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`button-hole-30rb`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase text-pink-ka xl:text-sm">
                                {{ currencyFormat(dataOptions.button_hole.data.options.option_1[0].price) }}
                            </div>
                            <!-- <span class="checkbox-inner pink"></span> -->
                        </label>
                    </div>
                </div>
            </div>

            <!-- button thread -->
            <div class="max-lg:col-span-2">
                <div class="flex items-center justify-between py-2 bg-blue-ka px-14">
                    <div class="text-lg font-bold tracking-wider text-white uppercase">12. button thread</div>
                </div>

                <div class="grid grid-cols-2 gap-2 p-4 mx-6 mt-4 border border-pink-ka xl:mx-0 lg:mr-6 lg:ml-0">
                    <div class="flex" v-for="(buttonThread, index) in dataOptions.button_thread.data.options.option_1" :key="index">
                        <input class="hidden" type="radio" name="button-thread" :value="buttonThread" v-model="form.buttonThread" :id="`botton-thread-${buttonThread.slug}`">
                        <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`botton-thread-${buttonThread.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">{{ index + 1 }}. {{ buttonThread.name }}</div>
                        </label>
                    </div>

                    <div class="self-end col-span-2 justify-self-end">
                        <input class="hidden" type="radio" name="button-thread-30rb" :id="`button-thread-30rb`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`button-thread-30rb`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase text-pink-ka xl:text-sm">
                                {{ currencyFormat(dataOptions.button_thread.data.options.option_1[0].price) }}
                            </div>
                            <!-- <span class="checkbox-inner pink"></span> -->
                        </label>
                    </div>
                </div>
            </div>

            <!-- stitch thread -->
            <div class="max-xl:col-span-2">
                <div class="flex items-center justify-between py-2 bg-blue-ka px-14">
                    <div class="text-lg font-bold tracking-wider text-white uppercase">13. stitch thread</div>
                </div>

                <div class="grid grid-cols-2 gap-2 p-4 mx-6 mt-4 border border-pink-ka xl:mr-14 xl:ml-0">
                    <div class="flex" v-for="(stitchThread, index) in dataOptions.stitch_thread.data.options.option_1" :key="index">
                        <input class="hidden" type="radio" name="stitch-thread" :value="stitchThread" v-model="form.stitchThread" :id="`stitch-thread-${stitchThread.slug}`">
                        <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`stitch-thread-${stitchThread.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">{{ index + 1 }}. {{ stitchThread.name }}</div>
                        </label>
                    </div>

                    <div class="self-end col-span-2 justify-self-end">
                        <input class="hidden" type="radio" name="stitch-thread-30rb" :id="`stitch-thread-30rb`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`stitch-thread-30rb`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase text-pink-ka xl:text-sm">
                                {{ currencyFormat(dataOptions.stitch_thread.data.options.option_1[0].price) }}
                            </div>
                            <!-- <span class="checkbox-inner pink"></span> -->
                        </label>
                    </div>
                </div>
            </div>
         </div>

         <!-- embroidery -->
         <div class="mt-6">
            <div class="flex items-center justify-between py-2 bg-blue-ka px-14">
                <div class="text-lg font-bold tracking-wider text-white uppercase">14. embroidery</div>
            </div>

            <div class="mx-6 mt-4 space-y-4 border border-pink-ka xl:mx-14">
                <div class="grid grid-cols-3">
                    <!-- position -->
                    <div class="p-4 space-y-4 border-b border-r border-pink-ka max-xl:col-span-3 border-b-dashed">
                        <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">position</div>
                        <div class="flex" v-for="(embroideryPosition, index) in dataOptions.embroidery.data.options.position" :key="index">
                            <input class="hidden" @click.native="embroidery.position = null" type="radio" name="embroidery-position" :value="embroideryPosition" v-model="embroidery.position" :id="`embroidery-position-${embroideryPosition.slug}`">
                            <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`embroidery-position-${embroideryPosition.slug}`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">{{ embroideryPosition.name }}</div>
                            </label>
                        </div>
                    </div>

                    <!-- color -->
                    <div class="p-4 space-y-4 border-b border-r border-pink-ka max-xl:col-span-3 border-b-dashed">
                        <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">color</div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="flex" v-for="(embroideryColor, index) in dataOptions.embroidery.data.options.color" :key="index">
                                <input @click.native="embroidery.color = null"  class="hidden" type="radio" name="embroidery-color" :value="embroideryColor" v-model="embroidery.color" :id="`embroidery-color-${embroideryColor.slug}`">
                                <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`embroidery-color-${embroideryColor.slug}`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">{{ (index + 1)+'. '+ embroideryColor.name }}</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- font type -->
                    <div class="p-4 space-y-4 border-b border-pink-ka max-xl:col-span-3 border-b-dashed">
                        <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">font type</div>
                        <div class="flex" v-for="(embroideryFontType, index) in dataOptions.embroidery.data.options.font_type" :key="index">
                            <input @click.native="embroidery.fontType = null" class="hidden" type="radio" name="embroidery-font-type" :value="embroideryFontType" v-model="embroidery.fontType" :id="`embroidery-font-type-${embroideryFontType.slug}`">
                            <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`embroidery-font-type-${embroideryFontType.slug}`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">{{ +(index + 1)+'. '+embroideryFontType.name }}</div>
                            </label>
                        </div>
                    </div>

                    <div class="col-span-3 p-4">
                        <div class="flex items-end gap-4 max-xl:flex-wrap">
                            <div class="flex items-end font-roboto">
                                <input v-model="embroidery.initialName.x" type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border border-primary-50 size-10">
                                <input v-model="embroidery.initialName.dot" type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-primary-50 border-y size-5">
                                <input v-model="embroidery.initialName.y" type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-l border-r border-primary-50 border-y size-10">
                                <InputBox :digitCount="10" @update:input="onInputIntialName($event)"/>
                            </div>
                            <div class="flex w-full gap-4">
                                <input type="text" v-model="embroidery.longName" class="block w-full h-8 p-2 text-sm text-gray-900 border border-primary-50 font-roboto">
                                <div class="whitespace-nowrap">
                                    <input class="hidden" type="radio" name="embroidery-50rb" :id="`embroidery-50rb`">
                                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`embroidery-50rb`">
                                        <div class="text-xs font-bold tracking-wider text-center uppercase text-pink-ka xl:text-sm">{{ currencyFormat(dataOptions.embroidery.data.options.price) }}</div>
                                        <!-- <span class="checkbox-inner pink"></span> -->
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 text-xs font-roboto text-pink-ka">*please write down your initial (font type 1,2,3) or long name (font type 4) into above boxes</div>
                    </div>
                </div>
            </div>
         </div>

         <div class="mt-6">
            <div class="flex items-center justify-between py-2 bg-blue-ka px-14">
                <div class="text-lg font-bold tracking-wider text-white uppercase">15. interlining</div>
            </div>

            <div class="flex items-center justify-between mx-6 mt-4 xl:mx-14">
                <div class="p-4 border border-pink-ka lg:w-1/2">
                    <div class="flex" v-for="(interlining, index) in dataOptions.interlining.data.options.option_1" :key="index">
                        <input class="hidden" type="radio" name="interlining" :value="interlining" v-model="form.interlining" :id="`interlining-${interlining.slug}`">
                        <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`interlining-${interlining.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">{{ interlining.name }}</div>
                        </label>
                    </div>
                </div>
                <div class="flex items-center justify-between w-full p-4 border border-pink-ka">
                    <div v-for="(interlining, index) in dataOptions.interlining.data.options.option_2" :key="index">
                        <input class="hidden" type="radio" name="interlining" :value="interlining" v-model="form.interlining" :id="`interlining-${interlining.slug}`">
                        <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`interlining-${interlining.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">{{ interlining.name  }}</div>
                        </label>
                    </div>
                    <div class="whitespace-nowrap">
                        <input class="hidden" type="radio" name="interlining-100rb" :id="`interlining-100rb`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`interlining-100rb`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase text-pink-ka xl:text-sm">{{ currencyFormat(dataOptions.interlining.data.options.option_2[0].price) }} </div>
                            <!-- <span class="checkbox-inner pink"></span> -->
                        </label>
                    </div>
                </div>
            </div>
        </div>

         <div class="mt-6">
            <div class="flex items-center justify-between py-2 bg-blue-ka px-14">
                <div class="text-lg font-bold tracking-wider text-white uppercase">15. sewing option</div>
            </div>

            <div class="flex flex-col items-center justify-between mx-6 mt-4 xl:mx-14">
                <div v-for="(sewing, index) in dataOptions.sewing_option.data.options" :key="index" class="flex items-center justify-between w-full p-4 border border-pink-ka">
                    <div v-for="(sewingOption, index) in sewing" :key="index">
                        <input class="hidden" type="radio" name="sewing-option" :value="sewingOption" v-model="form.sewingOption" :id="`sewing-${sewingOption.slug}`">
                        <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`sewing-${sewingOption.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase text-primary-50 xl:text-sm">{{ sewingOption.name }}</div>
                        </label>
                    </div>
                    <div class="whitespace-nowrap">
                        <input class="hidden" type="radio" name="sewing-100rb" :id="`sewing-100rb`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`sewing-100rb`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase text-pink-ka xl:text-sm"> {{ currencyFormat(dataOptions.sewing_option.data.options[index][0].price) }}</div>
                            <!-- <span class="checkbox-inner pink"></span> -->
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <div class="flex items-center justify-between py-2 bg-blue-ka px-14">
                <div class="text-lg font-bold tracking-wider text-white uppercase">17. TAPE (INNER COLLAR STAND & LOWER PLACKET)</div>
            </div>

            <div class="flex items-center justify-between gap-4 p-4 mx-6 mt-4 border border-pink-ka xl:mx-14">
                <div class="flex font-roboto">
                    <InputBox :digitCount="5" @update:input="onInputTape($event)"/>
                </div>
                <div class="w-9/12">
                    <input type="text" v-model="tape.lower" class="block w-full h-8 p-2 text-sm text-gray-900 border border-primary-50 font-roboto">
                </div>
                <div class="whitespace-nowrap">
                    <input class="hidden" type="radio" name="sewing-200rb" :id="`sewing-200rb`">
                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer font-roboto" :for="`sewing-200rb`">
                        <div class="text-xs font-bold tracking-wider text-center uppercase text-pink-ka xl:text-sm"> {{ currencyFormat(tape.price) }} </div>
                        <!-- <span class="checkbox-inner pink"></span> -->
                    </label>
                </div>
            </div>
        </div>

        <div class="mt-6 mb-32">
            <div class="flex items-center justify-between px-4 py-2 bg-blue-ka lg:px-14">
                <div class="font-bold tracking-widest text-white uppercase lg:text-xl">ADDITIONAL NOTES</div>
            </div>
            <div class="grid grid-cols-5 gap-3 px-6 my-10 xl:px-14">
                <div class="col-span-3">
                    <textarea class="w-full h-full p-2 border border-primary-50 font-roboto placeholder:font-josefin placeholder:tracking-widest placeholder-primary-50" v-model="additionalNote" name="" id="" placeholder="NOTE"></textarea>
                </div>
                <div class="col-span-2 space-y-2">
                        <div>
                            <small>Gift Card</small>
                            <VueNumber v-model="giftCard" v-bind="number_input"
                                 class="w-full px-4 pt-2 pb-1 border border-pink-ka text-primary-50 number-input" placeholder="GIFT CARD"/>
                        </div>
                        <div>
                            <small>Option Price</small>
                            <VueNumber v-model.lazy="price" v-bind="number_input" class="w-full px-4 pt-2 pb-1 border border-pink-ka text-primary-50 number-input" placeholder="RP"></VueNumber>
                        </div>
                    <div>
                        <button @click="amountOption()" class="w-full px-5 pt-3 pb-2 text-center bg-secondary text-pink-ka">APPLY PRICE</button>
                    </div>
                </div>
            </div>

            <div class="px-6 xl:px-14">
                <table class="text-sm uppercase text-primary-50">
                    <thead>
                        <tr class="*:border-primary-50 *:px-2 *:pt-2 *:pb-1 *:border *:text-center">
                            <th colspan="2">option</th>
                            <th>quantity</th>
                            <th>sub total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="*:border-primary-50 *:px-2 *:pt-2 *:pb-1 *:border *:text-center">
                            <td>a</td>
                            <td>30.000</td>
                            <td>{{ dataPrice?.[30000]?.quantity }}</td>
                            <td class="!text-left">{{ currencyFormat(dataPrice?.[30000]?.subTotal) }}</td>
                        </tr>
                        <tr class="*:border-primary-50 *:px-2 *:pt-2 *:pb-1 *:border *:text-center">
                            <td>b</td>
                            <td>50.000</td>
                            <td>{{ dataPrice?.[50000]?.quantity }}</td>
                            <td class="!text-left">{{ currencyFormat(dataPrice?.[50000]?.subTotal) }}</td>
                        </tr>
                        <tr class="*:border-primary-50 *:px-2 *:pt-2 *:pb-1 *:border *:text-center">
                            <td>c</td>
                            <td>70.000</td>
                            <td>{{ dataPrice?.[70000]?.quantity }}</td>
                            <td class="!text-left">{{ currencyFormat(dataPrice?.[70000]?.subTotal) }}</td>
                        </tr>
                        <tr class="*:border-primary-50 *:px-2 *:pt-2 *:pb-1 *:border *:text-center">
                            <td>d</td>
                            <td>100.000</td>
                            <td>{{ dataPrice?.[100000]?.quantity }}</td>
                            <td class="!text-left">{{ currencyFormat(dataPrice?.[100000]?.subTotal) }}</td>
                        </tr>
                        <tr class="*:border-primary-50 *:px-2 *:pt-2 *:pb-1 *:border *:text-center">
                            <td>e</td>
                            <td>200.000</td>
                            <td>{{ dataPrice?.[200000]?.quantity }}</td>
                            <td class="!text-left">{{ currencyFormat(dataPrice?.[200000]?.subTotal) }}</td>
                        </tr>
                        <tr class="*:border-primary-50 *:px-2 *:pt-2 *:pb-1 *:border *:text-center">
                            <td colspan="3">option total</td>
                            <td>{{ currencyFormat(dataPrice?.optionTotal) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</template>


<style lang="scss" scoped>
    .border-b-dashed {
        border-bottom-style: dashed!important;
    }

    input[type="radio"] + label span.checkbox-inner {
        @apply border-primary-50;

        &.pink {
            @apply border-pink-ka;
        }
    }

    input[type="radio"]:checked + label span.checkbox-inner {
        @apply border-primary-50 bg-primary-50;
        color: #fff;
        background-image: url("data:image/svg+xml,%3C%3Fxml version='1.0' encoding='UTF-8'%3F%3E%3Csvg width='14px' height='10px' viewBox='0 0 14 10' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'%3E%3C!-- Generator: Sketch 59.1 (86144) - https://sketch.com --%3E%3Ctitle%3Echeck%3C/title%3E%3Cdesc%3ECreated with Sketch.%3C/desc%3E%3Cg id='Page-1' stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'%3E%3Cg id='ios_modification' transform='translate(-27.000000, -191.000000)' fill='%23FFFFFF' fill-rule='nonzero'%3E%3Cg id='Group-Copy' transform='translate(0.000000, 164.000000)'%3E%3Cg id='ic-check-18px' transform='translate(25.000000, 23.000000)'%3E%3Cpolygon id='check' points='6.61 11.89 3.5 8.78 2.44 9.84 6.61 14 15.56 5.05 14.5 4'%3E%3C/polygon%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        background-size: 14px 10px;

        &.pink {
            @apply border-pink-ka bg-pink-ka;
        }
    }

    input[type="checkbox"] + label span.checkbox-inner {
        @apply border-primary-50;

        &.pink {
            @apply border-pink-ka;
        }
    }

    input[type="checkbox"]:checked + label span.checkbox-inner {
        @apply border-primary-50 bg-primary-50;
        color: #fff;
        background-image: url("data:image/svg+xml,%3C%3Fxml version='1.0' encoding='UTF-8'%3F%3E%3Csvg width='14px' height='10px' viewBox='0 0 14 10' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'%3E%3C!-- Generator: Sketch 59.1 (86144) - https://sketch.com --%3E%3Ctitle%3Echeck%3C/title%3E%3Cdesc%3ECreated with Sketch.%3C/desc%3E%3Cg id='Page-1' stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'%3E%3Cg id='ios_modification' transform='translate(-27.000000, -191.000000)' fill='%23FFFFFF' fill-rule='nonzero'%3E%3Cg id='Group-Copy' transform='translate(0.000000, 164.000000)'%3E%3Cg id='ic-check-18px' transform='translate(25.000000, 23.000000)'%3E%3Cpolygon id='check' points='6.61 11.89 3.5 8.78 2.44 9.84 6.61 14 15.56 5.05 14.5 4'%3E%3C/polygon%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        background-size: 14px 10px;

        &.pink {
            @apply border-pink-ka bg-pink-ka;
        }
    }

    .checkbox-inner {
        @apply flex justify-center items-center border-primary-50 border text-transparent size-7;
        background: transparent no-repeat center;

        &.pink {
            @apply border-pink-ka;
        }
    }
</style>
