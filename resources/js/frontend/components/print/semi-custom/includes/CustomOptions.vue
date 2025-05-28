<script setup>
    import { ref, defineProps, onMounted, watch } from 'vue';
    import  InputBox  from '@frontend/components/utils/fields/InputBox.vue';

    const number_input = {
        separator: '.',
        prefix: 'Rp ',
        precision: 0,
        masked: false,
    }


    const props = defineProps({
        dataSemiCustom: Object,
        dataConfig: Object
    });

    const formOption = ref(props.dataSemiCustom.option_form);
    const additionalNote = ref(props.dataSemiCustom.option_note);
    const price = ref(props.dataSemiCustom.option_additional_price);
    const discount = ref(0);

    const mainCleric = ref(null);
    const selectCleric = ref([]);

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

    const tape = ref({
        collar: '',
        lower: '',
        price: 0,
    });


    onMounted(() => {
        discount.value = props.dataSemiCustom.option_discount ?? 0;
    });

    const split = (string) => {
        return string.split('');
    }

    const currencyFormat = (value) => {
        if (!value){
            return ''
        }
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(
            value,
        )
    };

    const isChecked = (a) => {
        return a.selected ?? false;
    }

    const dataPriceTable = ref();

    watch(formOption.value, (items) => {
        dataPriceTable.value = groupAndCountByPrice(items);
    })

    onMounted(() => {
        dataPriceTable.value = groupAndCountByPrice(formOption.value);

        if (!_.isEmpty(formOption.value.embroidery)) {
                embroidery.value = formOption.value.embroidery;
            }

            if (!_.isEmpty(formOption.value.tape)) {
                tape.value = formOption.value.tape;
            }

            selectCleric.value = formOption.value.cleric?.data;

            additionalNote.value = formOption.value.additionalNote;

            const dataCleric = props.dataConfig.cleric.data.options; // dataCleric


            console.log(dataCleric);

            // if (!_.isEmpty(dataCleric)) {

                for (const clericOption of Object.entries(dataCleric)) {
                    for (const cleric of clericOption[1]) {
                        if (!_.isEmpty(formOption.value.cleric)) {
                            if (cleric.slug === formOption.value.cleric.slug) {
                                // push option to cleric
                                let selectCleric = {
                                    ...cleric,
                                    option: clericOption[0]
                                }
                                mainCleric.value = selectCleric;
                            }
                        }
                    }
                }
            // }
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
</script>

<template>
    <div class="relative mb-0 min-h-svh">

        <div class="gap-6 grid grid-cols-2">
            <div>
                <!-- collar -->
                <div id="collar">
                    <div class="wrap-opt-cat">
                        <div class="cat-name">02. Collar</div>
                    </div>

                    <div class="gap-2 grid grid-cols-6 my-4 px-4 whitespace-nowrap">
                        <div class="col-span-2">
                            <div class="flex" v-for="(collar, index) in dataConfig.collar.data.options.option_1" :key="index">
                                <input :checked="collar?.slug === formOption.collar?.slug" @click.native="formOption.collar = null" class="hidden" v-model="formOption.collar" :value="collar" type="radio" :id="'collar-'+collar.slug">
                                <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="'collar-'+collar.slug">
                                    <span class="checkbox-inner"></span>
                                    <div class="label-name">{{ collar.name }}</div>
                                </label>
                            </div>
                            <div class="mt-2 mb-3 font-roboto text-pink-ka text-xs">*No Additional Charge for above items</div>
                        </div>
                        <div class="col-span-4">
                            <div class="grid grid-cols-1 2xl:grid-cols-2 p-2 border border-pink-ka">
                                <div class="flex" v-for="(collar50, index) in dataConfig.collar.data.options.option_2" :key="index">
                                    <input :checked="collar50?.slug === formOption.collar?.slug" @click.native="formOption.collar = null" class="hidden" type="radio" v-model="formOption.collar" :value="collar50" :id="'collar-'+collar50.slug">
                                    <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="'collar-'+collar50.slug">
                                        <span class="checkbox-inner"></span>
                                        <div class="label-name">{{ collar50.name }}</div>
                                    </label>
                                </div>
                                <div class="justify-items-end self-end col-span-2">
                                    <div class="">
                                        <input class="hidden" type="radio" :id="`50rb`">
                                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`50rb`">
                                            <div class="font-bold text-pink-ka text-xs xl:text-sm print:text-base text-center uppercase tracking-wider">
                                                {{ currencyFormat(dataConfig.collar.data.options.option_2[0].price) }}
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 p-2 border border-pink-ka">
                                <div class="col-span-3">
                                    <div class="flex" v-for="(collar100, index) in dataConfig.collar.data.options.option_3" :key="index">
                                        <input  :checked="collar100?.slug === formOption.collar?.slug" @click.native="formOption.collar = null" class="hidden" v-model="formOption.collar" :value="collar100" type="radio" :id="`collar-${collar100.slug}`">
                                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`collar-${collar100.slug}`">
                                            <span class="checkbox-inner"></span>
                                            <div class="label-name">{{ collar100.name }}</div>
                                        </label>
                                    </div>
                                </div>
                                <div class="flex max-xl:flex-wrap items-center lg:gap-4 col-span-2 mt-4 fabric-code">
                                    <label for="fabric-code" class="text-primary-50 uppercase tracking-wider whitespace-nowrap">fabric code (4 digit)</label>
                                    <div class="flex font-roboto print-props">
                                        <InputBox :inputValue="formOption.collar?.fabricCode" />
                                    </div>
                                </div>
                                <div class="justify-items-end self-end col-3">
                                    <div class="">
                                        <input class="hidden" type="radio" :id="`100rb`">
                                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`100rb`">
                                            <div class="font-bold text-pink-ka text-xs xl:text-sm print:text-base text-center uppercase tracking-wider">
                                                {{ currencyFormat(dataConfig.collar.data.options.option_3[0].price) }}
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- cuffs -->
                <div id="cuffs">
                    <div class="wrap-opt-cat">
                        <div class="cat-name">03. cuffs</div>
                    </div>

                    <div class="gap-2 grid grid-cols-6 my-4 px-2 whitespace-nowrap">
                        <div class="col-span-2">
                            <div class="flex" v-for="(cuffs, index) in dataConfig.cuffs.data.options.option_1" :key="index">
                                <input :checked="formOption.cuffs?.slug == cuffs?.slug" @click.native="formOption.cuffs = null" class="hidden" type="radio" v-model="formOption.cuffs" :value="cuffs" :id="`cuffs-${cuffs.slug}`">
                                <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`cuffs-${cuffs.slug}`">
                                    <span class="checkbox-inner"></span>
                                    <div class="label-name">{{ cuffs.name }}</div>
                                </label>
                            </div>
                            <div class="mt-4 font-roboto text-pink-ka text-xs">*No Additional Charge for above items</div>
                        </div>
                        <div class="col-span-4">
                            <div>
                                <div class="gap-4 grid grid-cols-1 2xl:grid-cols-2 p-2 border border-pink-ka">
                                    <div class="flex" v-for="(cuffs, index) in dataConfig.cuffs.data.options.option_2" :key="index">
                                        <input :checked="formOption.cuffs?.slug == cuffs?.slug" @click.native="formOption.cuffs = null" class="hidden" v-model="formOption.cuffs" :value="cuffs" type="radio" :id="`cuffs-${cuffs.slug}`">
                                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`cuffs-${cuffs.slug}`">
                                            <span class="checkbox-inner"></span>
                                            <div class="label-name">{{ cuffs.name }}</div>
                                        </label>
                                    </div>
                                    <div class="justify-items-end self-end col-3">
                                        <div class="">
                                            <input class="hidden" type="radio" :id="`cuffs-70rb`">
                                            <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`cuffs-70rb`">
                                                <div class="font-bold text-pink-ka text-xs xl:text-sm print:text-base text-center uppercase tracking-wider">
                                                    {{ currencyFormat(dataConfig.cuffs.data.options.option_2[0].price) }}
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 p-2 border border-pink-ka">
                                    <div class="col-span-3">
                                        <div class="flex" v-for="(cuffs, index) in dataConfig.cuffs.data.options.option_3" :key="index">
                                            <input :checked="formOption.cuffs?.slug == cuffs?.slug" @click.native="formOption.cuffs = null" class="hidden" v-model="formOption.cuffs" :value="cuffs" type="radio" :id="`cuffs-${cuffs.slug}`">
                                            <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`cuffs-${cuffs.slug}`">
                                                <span class="checkbox-inner"></span>
                                                <div class="label-name">{{ cuffs.name }}</div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="flex max-lg:flex-wrap items-center lg:gap-8 col-span-2 mt-4 fabric-code">
                                        <label for="fabric-code" class="text-primary-50 uppercase tracking-wider whitespace-nowrap">fabric code (4 digit)</label>
                                        <div class="flex font-roboto print-props">
                                            <InputBox :inputValue="formOption.cuffs?.fabricCode" />
                                        </div>
                                    </div>
                                    <div class="justify-items-end self-end cols-3">
                                        <div class="">
                                            <input class="hidden" type="radio" :id="`cuffs-100rb`">
                                            <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`cuffs-100rb`">
                                                <div class="font-bold text-pink-ka text-xs xl:text-sm print:text-base text-center uppercase tracking-wider">
                                                    {{ currencyFormat(dataConfig.cuffs.data.options.option_3[0].price) }}
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Front Body -->
                            <div class="my-6">
                                <div class="wrap-opt-cat">
                                    <div class="cat-name">04. FRONT BODY</div>
                                </div>
                                <div class="flex justify-between mt-4 p-2 border border-pink-ka">
                                    <div class="flex" v-for="(frontBody, index) in dataConfig.front_body.data.options.option_1" :key="index">
                                        <input :checked="formOption.frontBody?.slug == frontBody?.slug" @click.native="formOption.frontBody = null" class="hidden" type="radio" :value="frontBody" v-model="formOption.frontBody"  :id="`hidden-placket`">
                                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`hidden-placket`">
                                            <span class="checkbox-inner"></span>
                                            <div class="label-name">{{ frontBody.name }}</div>
                                        </label>
                                    </div>
                                    <div class="">
                                        <input class="hidden" type="radio" :id="`front-bdy-100`">
                                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`front-bdy-100`">
                                            <div class="font-bold text-pink-ka text-xs xl:text-sm print:text-base text-center uppercase tracking-wider">
                                                {{ currencyFormat(dataConfig.front_body.data.options.option_1[0].price) }}
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- button & body snap button -->
                <div class="flex gap-4 *:w-full">
                    <div class="">
                        <div class="wrap-opt-cat">
                            <div class="cat-name">08. BUTTON</div>
                        </div>
                        <div class="flex justify-between max-xl:mx-2 mt-4 max-xl:mb-4 ml-2 p-2 border border-pink-ka">
                            <div v-for="(button, index) in dataConfig.button.data.options.option_1" :key="index">
                                <input :checked="formOption.button?.slug == button?.slug" @click.native="formOption.button = null" :value="button" class="hidden" type="radio" v-model="formOption.button" :id="`btn-${button.slug}`">
                                <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`btn-${button.slug}`">
                                    <span class="checkbox-inner"></span>
                                    <div class="label-name">{{ button.name }}</div>
                                </label>
                            </div>
                            <div class="flex font-roboto print-props">
                                <InputBox :inputValue="formOption.button.buttonCode" />
                            </div>
                            <div class="">
                                <input class="hidden" type="radio" :id="`button-100`">
                                <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`button-100`">
                                    <div class="font-bold text-pink-ka text-xs xl:text-sm print:text-base text-center uppercase tracking-wider">
                                        {{ currencyFormat(dataConfig.button.data.options.option_1[0].price) }}
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="wrap-opt-cat">
                            <div class="cat-name">09. BODY SNAP BUTTON</div>
                        </div>
                        <div class="flex justify-between max-xl:mx-2 mt-4 mr-2 p-2 border border-pink-ka">
                            <div v-for="bodySnapButton in dataConfig.body_snap_button.data.options.option_1" :key="bodySnapButton">
                                <input class="hidden" @click.native="formOption.bodySnapButton = null" :value="bodySnapButton" v-model="formOption.bodySnapButton" type="radio" :id="`${bodySnapButton.slug}`">
                                <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`${bodySnapButton.slug}`">
                                    <span class="checkbox-inner"></span>
                                    <div class="label-name">{{ bodySnapButton.name }}</div>
                                </label>
                            </div>
                            <div class="">
                                <input class="hidden" type="radio" :id="`bdy-snap-50`">
                                <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`bdy-snap-50`">
                                    <div class="font-bold text-pink-ka text-xs xl:text-sm print:text-base text-center uppercase tracking-wider">
                                        {{ currencyFormat(dataConfig.body_snap_button.data.options.option_1[0].price) }}
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- cleric -->
                <div class="mt-2">
                    <div class="wrap-opt-cat">
                         <div class="cat-name">10. Cleric</div>
                     </div>

                    <div v-for="(dataCleric, index) in props.dataConfig.cleric.data.options" :key="index"
                        class="space-y-4 mx-2 mt-4 p-4 border border-pink-ka">
                        <div v-for="(subCeleric, index) in dataCleric" :key="index" class="flex gap-2">
                            <div>
                                <input @click.native="mainCleric = null" :checked="subCeleric.slug == mainCleric?.slug" @change="onChangeCleric" class="hidden" type="radio" v-model="mainCleric"  :value="subCeleric" :id="`${subCeleric.slug}`">
                                <label class="items-center gap-1 grid grid-cols-2 rounded h-full cursor-pointer" :for="`${subCeleric.slug}`">
                                    <div class="font-bold text-primary-50 text-sm xl:text-sm text-center uppercase tracking-wider">{{ subCeleric.no }}</div>
                                    <span class="checkbox-inner"></span>
                                </label>
                            </div>
                            <div class="flex gap-2 checkbox-cleric" :class="{ 'pointer-events-none': mainCleric?.slug != subCeleric.slug}">
                                <div v-for="clericItems in subCeleric.data">
                                    <input @change="onSelectCleric" class="hidden" type="checkbox" v-model="selectCleric" :value="clericItems" :id="`cleric-${subCeleric.no}-${clericItems.slug}`">
                                    <label class="flex items-center gap-2 rounded h-full cursor-pointer" :for="`cleric-${subCeleric.no}-${clericItems.slug}`">
                                        <span class="checkbox-inner"></span>
                                        <div class="label-name">{{ clericItems.name }}</div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-self-end self-end gap-2">
                            <div class="flex flex-col gap-1 col-span-2 mt-4 fabric-code">
                                    <label for="fabric-code" class="text-primary-50 uppercase tracking-wider whitespace-nowrap">fabric code (4 digit)</label>
                                    <div class="flex font-roboto" v-if="mainCleric?.option == index">
                                        <InputBox :digitCount="4" @update:input="onInputBox($event, 'cleric', 'fabricCode')" :inputValue="formOption.cleric?.fabricCode"/>
                                    </div>
                                    <div class="flex font-roboto" v-else>
                                        <InputBox :digitCount="4" @update:input="onInputBox($event, 'cleric', 'fabricCode')" />
                                    </div>
                                </div>
                            <div class="self-end">
                                <input class="hidden" type="radio" :id="`cleric-50rb`">
                                <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`cleric-50rb`">
                                    <div class="font-bold text-pink-ka text-xs xl:text-sm text-center uppercase tracking-wider">{{ currencyFormat(dataCleric[0].price) }}</div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                 <div class="flex items-center gap-2 bg-secondary mx-2 mt-4 p-4 print-props">
                    <label for="fabric-code" class="text-primary-50 text-sm uppercase tracking-wider whitespace-nowrap">name</label>
                    <input type="text" :value="props.dataSemiCustom.name" class="block p-2 border border-primary-50 w-full h-8 font-roboto text-gray-900 text-sm">
                 </div>
            </div>

            <div>
                <!-- button hole, button thread, stitch thread -->
                <div class="gap-1 grid grid-cols-3 mt-0 whitespace-nowrap">
                   <!-- button hole -->
                   <div class="">
                       <div class="wrap-opt-cat">
                           <div class="font-bold text-white text-lg uppercase tracking-wider">11. button hole</div>
                       </div>

                       <div class="buttons-wrapper">
                           <div class="buttons-layout">
                               <div class="flex" v-for="(buttonHole, index) in dataConfig.button_hole.data.options.option_1" :key="index">
                                   <input :checked="formOption.buttonHole?.slug == buttonHole?.slug" @click.native="formOption.buttonHole = null" class="hidden" type="radio" :value="buttonHole" v-model="formOption.buttonHole"  :id="`botton-hole-${buttonHole.slug}`">
                                   <label class="flex items-center gap-x-2 rounded h-full cursor-pointer" :for="`botton-hole-${buttonHole.slug}`">
                                       <span class="checkbox-inner"></span>
                                       <div class="label-name">{{ index + 1 }}. {{ buttonHole.name }}</div>
                                   </label>
                               </div>
                            </div>

                            <div class="button-price-label">
                               <div class="justify-self-end self-end col-span-2">
                                   <input class="hidden" type="radio" :id="`button-hole-30rb`">
                                   <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`button-hole-30rb`">
                                       <div class="font-bold text-pink-ka text-xs xl:text-sm print:text-base text-center uppercase tracking-wider">
                                           {{ currencyFormat(dataConfig.button_hole.data.options.option_1[0].price) }}
                                       </div>
                                       <!-- <span class="checkbox-inner pink"></span> -->
                                   </label>
                               </div>
                           </div>
                       </div>

                   </div>

                   <!-- button thread -->
                   <div class="">
                       <div class="wrap-opt-cat">
                           <div class="font-bold text-white text-lg uppercase tracking-wider">12. button thread</div>
                       </div>
                       <div class="buttons-wrapper">
                            <div class="buttons-layout">
                                <div class="flex" v-for="(buttonThread, index) in dataConfig.button_thread.data.options.option_1" :key="index">
                                    <input :checked="formOption.buttonThread?.slug == buttonThread?.slug" @click.native="formOption.buttonThread = null" class="hidden" type="radio" :value="buttonThread" v-model="formOption.buttonThread" :id="`botton-thread-${buttonThread.slug}`">
                                    <label class="flex items-center gap-2 rounded h-full cursor-pointer" :for="`botton-thread-${buttonThread.slug}`">
                                        <span class="checkbox-inner"></span>
                                        <div class="label-name">{{ index + 1 }}. {{ buttonThread.name }}</div>
                                    </label>
                                </div>
                            </div>

                           <div class="button-price-label">
                               <div class="justify-self-end self-end col-span-2">
                                   <input class="hidden" type="radio" :id="`button-thread-30rb`">
                                   <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`button-thread-30rb`">
                                       <div class="font-bold text-pink-ka text-xs xl:text-sm print:text-base text-center uppercase tracking-wider">
                                           {{ currencyFormat(dataConfig.button_thread.data.options.option_1[0].price) }}
                                       </div>
                                       <!-- <span class="checkbox-inner pink"></span> -->
                                   </label>
                               </div>
                           </div>
                       </div>
                   </div>

                   <!-- stitch thread -->
                   <div class="">
                       <div class="wrap-opt-cat">
                           <div class="font-bold text-white text-lg uppercase tracking-wider">13. stitch thread</div>
                       </div>

                       <div class="buttons-wrapper">
                           <div class="buttons-layout">
                               <div class="flex" v-for="(stitchThread, index) in dataConfig.stitch_thread.data.options.option_1" :key="index">
                                   <input :checked="formOption.stitchThread?.slug == stitchThread?.slug" @click.native="formOption.stitchThread = null" class="hidden" type="radio" :value="stitchThread" v-model="formOption.stitchThread" :id="`stitch-thread-${stitchThread.slug}`">
                                   <label class="flex items-center gap-2 rounded h-full cursor-pointer" :for="`stitch-thread-${stitchThread.slug}`">
                                       <span class="checkbox-inner"></span>
                                       <div class="label-name">{{ index + 1 }}. {{ stitchThread.name }}</div>
                                   </label>
                               </div>
                            </div>
                            <div class="button-price-label">
                               <div class="justify-self-end self-end col-span-2">
                                   <input class="hidden" type="radio" :id="`stitch-thread-30rb`">
                                   <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`stitch-thread-30rb`">
                                       <div class="font-bold text-pink-ka text-xs xl:text-sm print:text-base text-center uppercase tracking-wider">
                                           {{ currencyFormat(dataConfig.stitch_thread.data.options.option_1[0].price) }}
                                       </div>
                                       <!-- <span class="checkbox-inner pink"></span> -->
                                   </label>
                               </div>
                           </div>
                       </div>
                   </div>
                </div>

                <!-- embroidery -->
                <div class="mt-2">
                    <div class="wrap-opt-cat">
                       <div class="font-bold text-white text-lg uppercase tracking-wider">14. embroidery</div>
                   </div>

                    <div class="space-y-4 mx-2 mt-4 border border-pink-ka">
                        <div class="grid grid-cols-3">
                            <!-- position -->
                            <div class="space-y-2 p-2 border-pink-ka border-r border-b border-b-dashed">
                                <div class="label-name">position</div>
                                <div class="flex" v-for="(embroideryPosition, index) in props.dataConfig.embroidery.data.options.position" :key="index">
                                    <input class="hidden" @click.native="embroidery.position = null" type="radio" :value="embroideryPosition" v-model="embroidery.position" :id="`embroidery-position-${embroideryPosition.slug}`">
                                    <label class="flex items-center gap-2 rounded h-full cursor-pointer" :for="`embroidery-position-${embroideryPosition.slug}`">
                                        <span class="checkbox-inner"></span>
                                        <div class="label-name">{{ embroideryPosition.name }}</div>
                                    </label>
                                </div>
                            </div>

                            <!-- color -->
                            <div class="space-y-2 p-2 border-pink-ka border-r border-b border-b-dashed">
                                <div class="label-name">color</div>
                                <div class="gap-2 grid grid-cols-2 grid-rows-5 grid-flow-col">
                                    <div class="flex" v-for="(embroideryColor, index) in props.dataConfig.embroidery.data.options.color" :key="index">
                                        <input @click.native="embroidery.color = null"  class="hidden" type="radio" :value="embroideryColor" v-model="embroidery.color" :id="`embroidery-color-${embroideryColor.slug}`">
                                        <label class="flex items-center gap-2 rounded h-full cursor-pointer" :for="`embroidery-color-${embroideryColor.slug}`">
                                            <span class="checkbox-inner"></span>
                                            <div class="label-name">{{ index + 1 }}. {{embroideryColor.name }}</div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- font type -->
                            <div class="space-y-2 p-2 border-pink-ka border-b border-b-dashed">
                                <div class="label-name">font type</div>
                                <div class="flex" v-for="(embroideryFontType, index) in props.dataConfig.embroidery.data.options.font_type" :key="index">
                                    <input @click.native="embroidery.fontType = null" class="hidden" type="radio" :value="embroideryFontType" v-model="embroidery.fontType" :id="`embroidery-font-type-${embroideryFontType.slug}`">
                                    <label class="flex items-center gap-2 rounded h-full cursor-pointer" :for="`embroidery-font-type-${embroideryFontType.slug}`">
                                        <span class="checkbox-inner"></span>
                                        <div class="label-name">{{ +(index + 1)+'. '+embroideryFontType.name }}</div>
                                    </label>
                                </div>
                            </div>

                            <div class="col-span-3 p-4">
                                <div class="flex items-end gap-4">
                                    <div class="flex items-end font-roboto print-props-embroidery">
                                        <input v-model="embroidery.initialName.x" type="text" maxlength="1" class="block p-2 border border-primary-50 size-10 text-gray-900 text-sm text-center">
                                        <input v-model="embroidery.initialName.dot" type="text" maxlength="1" class="block border-primary-50 border-y w-8 h-6 text-gray-900 text-sm text-center">
                                        <input v-model="embroidery.initialName.y" type="text" maxlength="1" class="block p-2 border-primary-50 border-y border-r border-l size-10 text-gray-900 text-sm text-center">
                                        <InputBox :digitCount="10" @update:input="onInputIntialName($event)" :inputValue="embroidery.initialName?.z"/>
                                    </div>
                                    <div class="flex gap-4 w-full print-props-embroidery">
                                        <input type="text" v-model="embroidery.longName" class="block p-2 border border-primary-50 w-full h-8 font-roboto text-gray-900 text-sm print-props">
                                        <div class="whitespace-nowrap">
                                            <input class="hidden" type="radio" :id="`embroidery-50rb`">
                                            <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`embroidery-50rb`">
                                                <div class="font-bold text-pink-ka text-xs xl:text-sm text-center uppercase tracking-wider">{{ currencyFormat(props.dataConfig.embroidery.data.options.price) }}</div>
                                                <!-- <span class="checkbox-inner pink"></span> -->
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 font-roboto text-pink-ka text-xs">*please write down your initial (font type 1,2,3) or long name (font type 4) into above boxes</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- interlining -->
                <div class="mt-2">
                   <div class="wrap-opt-cat">
                       <div class="font-bold text-white text-lg uppercase tracking-wider">15. interlining</div>
                   </div>

                   <div class="flex justify-between items-center mx-2 mt-4">
                       <div class="p-4 border border-pink-ka lg:w-1/2">
                           <div class="flex" v-for="(interlining, index) in dataConfig.interlining.data.options.option_1" :key="index">
                               <input @click.native="formOption.interlining = null" class="hidden" type="radio" :value="interlining" v-model="formOption.interlining" :id="`interlining-${interlining.slug}`">
                               <label class="flex items-center gap-2 rounded h-full cursor-pointer" :for="`interlining-${interlining.slug}`">
                                   <span class="checkbox-inner"></span>
                                   <div class="label-name">{{ interlining.name }}</div>
                               </label>
                           </div>
                       </div>
                       <div class="flex justify-between items-center p-4 border border-pink-ka w-full">
                           <div v-for="(interlining, index) in dataConfig.interlining.data.options.option_2" :key="index">
                               <input @click.native="formOption.interlining = null" class="hidden" type="radio" :value="interlining" v-model="formOption.interlining" :id="`interlining-${interlining.slug}`">
                               <label class="flex items-center gap-2 rounded h-full cursor-pointer" :for="`interlining-${interlining.slug}`">
                                   <span class="checkbox-inner"></span>
                                   <div class="label-name">{{ interlining.name  }}</div>
                               </label>
                           </div>
                           <div class="whitespace-nowrap">
                               <input class="hidden" type="radio" :id="`interlining-100rb`">
                               <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`interlining-100rb`">
                                   <div class="font-bold text-pink-ka text-xs xl:text-sm print:text-base text-center uppercase tracking-wider">{{ currencyFormat(dataConfig.interlining.data.options.option_2[0].price) }} </div>
                                   <!-- <span class="checkbox-inner pink"></span> -->
                               </label>
                           </div>
                       </div>
                   </div>
               </div>

               <!-- sewing option -->
               <div class="mt-2">
                   <div class="wrap-opt-cat">
                       <div class="font-bold text-white text-lg uppercase tracking-wider">15. sewing option</div>
                   </div>

                   <div class="flex flex-col justify-between items-center mx-2 mt-4">
                       <div v-for="(sewing, index) in dataConfig.sewing_option.data.options" :key="index" class="flex justify-between items-center p-4 border border-pink-ka w-full">
                           <div v-for="(sewingOption, index) in sewing" :key="index">
                               <input @click.native="formOption.sewingOption = null" class="hidden" type="radio" :value="sewingOption" v-model="formOption.sewingOption" :id="`sewing-${sewingOption.slug}`">
                               <label class="flex items-center gap-2 rounded h-full cursor-pointer" :for="`sewing-${sewingOption.slug}`">
                                   <span class="checkbox-inner"></span>
                                   <div class="label-name">{{ sewingOption.name }}</div>
                               </label>
                           </div>
                           <div class="whitespace-nowrap">
                               <input class="hidden" type="radio" :id="`sewing-100rb`">
                               <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`sewing-100rb`">
                                   <div class="font-bold text-pink-ka text-xs xl:text-sm print:text-base text-center uppercase tracking-wider"> {{ currencyFormat(dataConfig.sewing_option.data.options[index][0].price) }}</div>
                                   <!-- <span class="checkbox-inner pink"></span> -->
                               </label>
                           </div>
                       </div>
                   </div>
               </div>

               <div class="mt-2">
                   <div class="wrap-opt-cat">
                       <div class="font-bold text-white text-lg uppercase tracking-wider">17. TAPE (INNER COLLAR STAND & LOWER PLACKET)</div>
                   </div>

                   <div class="flex justify-between items-center gap-4 mx-2 mt-4 p-4 border border-pink-ka">
                       <div class="flex font-roboto print-props">
                        <InputBox :digitCount="5" :inputValue="formOption.tape?.collar" />
                       </div>
                       <div class="w-9/12 print-props">
                           <input type="text" v-model="tape.lower" class="block p-2 border border-primary-50 w-full h-8 font-roboto text-gray-900 text-sm">
                       </div>
                       <div class="whitespace-nowrap">
                           <input class="hidden" type="radio" :id="`sewing-200rb`">
                           <label class="flex items-center gap-4 px-2 rounded h-full font-roboto cursor-pointer" :for="`sewing-200rb`">
                               <div class="font-bold text-pink-ka text-xs xl:text-sm print:text-base text-center uppercase tracking-wider"> {{ currencyFormat(tape.price ?? 200000)  }} </div>
                               <!-- <span class="checkbox-inner pink"></span> -->
                           </label>
                       </div>
                   </div>
               </div>

               <div class="mt-2 m">
                   <div class="flex justify-between items-center bg-blue-ka px-4 py-2">
                       <div class="font-bold text-white lg:text-xl uppercase tracking-widest">ADDITIONAL NOTES</div>
                   </div>
                   <div class="gap-3 grid grid-cols-6 mt-2 px-2">
                        <div class="col-span-6">
                            <textarea class="p-2 border border-primary-50 w-full h-full font-roboto placeholder:font-josefin placeholder:tracking-widest placeholder-primary-50" v-model="additionalNote" id="" placeholder="NOTE"></textarea>
                        </div>
                        <div class="col-span-6">
                            <table class="w-full text-primary-50 text-sm uppercase">
                                <thead>
                                    <tr class="*:border *:border-primary-50 *:text-center">
                                        <th colspan="2">option</th>
                                        <th>quantity</th>
                                        <th>sub total</th>
                                    </tr>
                                </thead>
                                <tbody class="print-props-md">
                                    <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-50 *:text-center">
                                        <td>a</td>
                                        <td>30.000</td>
                                        <td>{{ dataPriceTable?.[30000]?.quantity }}</td>
                                        <td class="!text-left">{{ currencyFormat(dataPriceTable?.[30000]?.subTotal) }}</td>
                                    </tr>
                                    <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-50 *:text-center">
                                        <td>b</td>
                                        <td>50.000</td>
                                        <td>{{ dataPriceTable?.[50000]?.quantity }}</td>
                                        <td class="!text-left">{{ currencyFormat(dataPriceTable?.[50000]?.subTotal) }}</td>
                                    </tr>
                                    <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-50 *:text-center">
                                        <td>c</td>
                                        <td>70.000</td>
                                        <td>{{ dataPriceTable?.[70000]?.quantity }}</td>
                                        <td class="!text-left">{{ currencyFormat(dataPriceTable?.[70000]?.subTotal) }}</td>
                                    </tr>
                                    <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-50 *:text-center">
                                        <td>d</td>
                                        <td>100.000</td>
                                        <td>{{ dataPriceTable?.[100000]?.quantity }}</td>
                                        <td class="!text-left">{{ currencyFormat(dataPriceTable?.[100000]?.subTotal) }}</td>
                                    </tr>
                                    <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-50 *:text-center">
                                        <td>e</td>
                                        <td>200.000</td>
                                        <td>{{ dataPriceTable?.[200000]?.quantity }}</td>
                                        <td class="!text-left">{{ currencyFormat(dataPriceTable?.[200000]?.subTotal) }}</td>
                                    </tr>
                                    <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-50 *:text-center">
                                        <td colspan="3">option total</td>
                                        <td>{{ currencyFormat(dataPriceTable?.optionTotal) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                   </div>
               </div>
            </div>
        </div>
    </div>
</template>


<style lang="scss" scoped>
    .wrap-opt-cat{
        @apply flex justify-between items-center bg-blue-ka px-4 py-1;

        .cat-name {
            @apply font-bold tracking-widest text-white uppercase
        }

    }

    .label-name {
        @apply font-bold text-primary-50 text-xs print:text-lg text-center uppercase tracking-wider;
    }

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
        @apply bg-primary-50 border-primary-50;
        color: #fff;
        background-image: url("data:image/svg+xml,%3C%3Fxml version='1.0' encoding='UTF-8'%3F%3E%3Csvg width='14px' height='10px' viewBox='0 0 14 10' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'%3E%3C!-- Generator: Sketch 59.1 (86144) - https://sketch.com --%3E%3Ctitle%3Echeck%3C/title%3E%3Cdesc%3ECreated with Sketch.%3C/desc%3E%3Cg id='Page-1' stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'%3E%3Cg id='ios_modification' transform='translate(-27.000000, -191.000000)' fill='%23FFFFFF' fill-rule='nonzero'%3E%3Cg id='Group-Copy' transform='translate(0.000000, 164.000000)'%3E%3Cg id='ic-check-18px' transform='translate(25.000000, 23.000000)'%3E%3Cpolygon id='check' points='6.61 11.89 3.5 8.78 2.44 9.84 6.61 14 15.56 5.05 14.5 4'%3E%3C/polygon%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        background-size: 14px 10px;

        &.pink {
            @apply bg-pink-ka border-pink-ka;
        }
    }

    input[type="checkbox"] + label span.checkbox-inner {
        @apply border-primary-50;

        &.pink {
            @apply border-pink-ka;
        }
    }

    input[type="checkbox"]:checked + label span.checkbox-inner {
        @apply bg-primary-50 border-primary-50;
        color: #fff;
        background-image: url("data:image/svg+xml,%3C%3Fxml version='1.0' encoding='UTF-8'%3F%3E%3Csvg width='14px' height='10px' viewBox='0 0 14 10' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'%3E%3C!-- Generator: Sketch 59.1 (86144) - https://sketch.com --%3E%3Ctitle%3Echeck%3C/title%3E%3Cdesc%3ECreated with Sketch.%3C/desc%3E%3Cg id='Page-1' stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'%3E%3Cg id='ios_modification' transform='translate(-27.000000, -191.000000)' fill='%23FFFFFF' fill-rule='nonzero'%3E%3Cg id='Group-Copy' transform='translate(0.000000, 164.000000)'%3E%3Cg id='ic-check-18px' transform='translate(25.000000, 23.000000)'%3E%3Cpolygon id='check' points='6.61 11.89 3.5 8.78 2.44 9.84 6.61 14 15.56 5.05 14.5 4'%3E%3C/polygon%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        background-size: 14px 10px;

        &.pink {
            @apply bg-pink-ka border-pink-ka;
        }
    }

    .checkbox-inner {
        @apply flex justify-center items-center border border-primary-50 size-7 text-transparent;
        background: transparent no-repeat center;

        &.pink {
            @apply border-pink-ka;
        }
    }
</style>
