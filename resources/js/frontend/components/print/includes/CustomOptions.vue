<script setup>
    import { ref, defineProps, onMounted, watch } from 'vue';

    import { component as VueNumber } from '@coders-tm/vue-number-format';

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
                            <div class="border-pink-ka grid grid-cols-1 2xl:grid-cols-2 p-2 border">
                                <div class="flex" v-for="(collar50, index) in dataConfig.collar.data.options.option_2" :key="index">
                                    <input :checked="collar50?.slug === formOption.collar?.slug" @click.native="formOption.collar = null" class="hidden" type="radio" v-model="formOption.collar" :value="collar50" :id="'collar-'+collar50.slug">
                                    <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="'collar-'+collar50.slug">
                                        <span class="checkbox-inner"></span>
                                        <div class="label-name">{{ collar50.name }}</div>
                                    </label>
                                </div>
                                <div class="justify-items-end col-span-2 self-end">
                                    <div class="">
                                        <input class="hidden" type="radio" name="collar-50" :id="`50rb`">
                                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`50rb`">
                                            <div class="font-bold text-center text-pink-ka text-xs print:text-base xl:text-sm uppercase tracking-wider">
                                                {{ currencyFormat(dataConfig.collar.data.options.option_2[0].price) }}
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="border-pink-ka grid grid-cols-3 p-2 border">
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
                                        <div ref="inputCont" class="box-input-wrapper" v-if="formOption.collar?.fabricCode !== undefined">
                                            <input
                                                v-for="(digit, index) in split(formOption.collar.fabricCode)"
                                                type="text"
                                                maxlength="1"
                                                :value="digit"
                                                class="box-input"
                                            >
                                        </div>
                                        <div v-else class="box-input-wrapper">
                                            <input
                                                v-for="(digit, index) in 4"
                                                type="text"
                                                maxlength="1"
                                                class="box-input"
                                            >
                                        </div>
                                        <!-- <boxInput :digitCount="4" @update:input="onInputBox($event, 'collar', 'fabricCode')"/> -->
                                    </div>
                                </div>
                                <div class="justify-items-end col-3 self-end">
                                    <div class="">
                                        <input class="hidden" type="radio" name="collar-100" :id="`100rb`">
                                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`100rb`">
                                            <div class="font-bold text-center text-pink-ka text-xs print:text-base xl:text-sm uppercase tracking-wider">
                                                {{ currencyFormat(dataConfig.collar.data.options.option_3[0].price) }}
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
                    <div class="wrap-opt-cat">
                        <div class="cat-name">03. cuffs</div>
                    </div>

                    <div class="gap-2 grid grid-cols-6 my-4 px-2 whitespace-nowrap">
                        <div class="col-span-2">
                            <div class="flex" v-for="(cuffs, index) in dataConfig.cuffs.data.options.option_1" :key="index">
                                <input :checked="formOption.cuffs?.slug == cuffs?.slug" @click.native="formOption.cuffs = null" class="hidden" type="radio" name="cuffs" v-model="formOption.cuffs" :value="cuffs" :id="`cuffs-${cuffs.slug}`">
                                <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`cuffs-${cuffs.slug}`">
                                    <span class="checkbox-inner"></span>
                                    <div class="label-name">{{ cuffs.name }}</div>
                                </label>
                            </div>
                            <div class="mt-4 font-roboto text-pink-ka text-xs">*No Additional Charge for above items</div>
                        </div>
                        <div class="col-span-4">
                            <div>
                                <div class="gap-4 border-pink-ka grid grid-cols-1 2xl:grid-cols-2 p-2 border">
                                    <div class="flex" v-for="(cuffs, index) in dataConfig.cuffs.data.options.option_2" :key="index">
                                        <input :checked="formOption.cuffs?.slug == cuffs?.slug" @click.native="formOption.cuffs = null" class="hidden" v-model="formOption.cuffs" :value="cuffs" type="radio" name="cuffs" :id="`cuffs-${cuffs.slug}`">
                                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`cuffs-${cuffs.slug}`">
                                            <span class="checkbox-inner"></span>
                                            <div class="label-name">{{ cuffs.name }}</div>
                                        </label>
                                    </div>
                                    <div class="justify-items-end col-3 self-end">
                                        <div class="">
                                            <input class="hidden" type="radio" name="cuffs-70" :id="`cuffs-70rb`">
                                            <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`cuffs-70rb`">
                                                <div class="font-bold text-center text-pink-ka text-xs print:text-base xl:text-sm uppercase tracking-wider">
                                                    {{ currencyFormat(dataConfig.cuffs.data.options.option_2[0].price) }}
                                                </div>
                                                <!-- <span class="checkbox-inner pink"></span> -->
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-pink-ka grid grid-cols-3 p-2 border">
                                    <div class="col-span-3">
                                        <div class="flex" v-for="(cuffs, index) in dataConfig.cuffs.data.options.option_3" :key="index">
                                            <input :checked="formOption.cuffs?.slug == cuffs?.slug" @click.native="formOption.cuffs = null" class="hidden" v-model="formOption.cuffs" :value="cuffs" type="radio" name="cuffs" :id="`cuffs-${cuffs.slug}`">
                                            <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`cuffs-${cuffs.slug}`">
                                                <span class="checkbox-inner"></span>
                                                <div class="label-name">{{ cuffs.name }}</div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="flex max-lg:flex-wrap items-center lg:gap-8 col-span-2 mt-4 fabric-code">
                                        <label for="fabric-code" class="text-primary-50 uppercase tracking-wider whitespace-nowrap">fabric code (4 digit)</label>
                                        <div class="flex font-roboto print-props">
                                            <div ref="inputCont" class="box-input-wrapper" v-if="formOption.cuffs?.fabricCode !== undefined && formOption.cuffs?.fabricCode !== null">
                                                <input
                                                    v-for="(digit, index) in split(formOption.cuffs.fabricCode ?? '')"
                                                    type="text"
                                                    maxlength="1"
                                                    :value="digit"
                                                    class="box-input"
                                                >
                                            </div>
                                            <div v-else class="box-input-wrapper">
                                                <input
                                                    v-for="(digit, index) in 4"
                                                    type="text"
                                                    maxlength="1"
                                                    class="box-input"
                                                >
                                            </div>
                                            <!-- <boxInput :digitCount="4" @update:input="onInputBox($event, 'cuffs', 'fabricCode')"/> -->
                                        </div>
                                    </div>
                                    <div class="justify-items-end cols-3 self-end">
                                        <div class="">
                                            <input class="hidden" type="radio" name="cuffs-100" :id="`cuffs-100rb`">
                                            <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`cuffs-100rb`">
                                                <div class="font-bold text-center text-pink-ka text-xs print:text-base xl:text-sm uppercase tracking-wider">
                                                    {{ currencyFormat(dataConfig.cuffs.data.options.option_3[0].price) }}
                                                </div>
                                                <!-- <span class="checkbox-inner pink"></span> -->
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
                                <div class="flex justify-between border-pink-ka mt-4 p-2 border">
                                    <div class="flex" v-for="(frontBody, index) in dataConfig.front_body.data.options.option_1" :key="index">
                                        <input :checked="formOption.cuffs?.slug == frontBody?.slug" @click.native="formOption.frontBody = null" class="hidden" type="radio" name="front-body" :value="frontBody" v-model="formOption.frontBody"  :id="`hidden-placket`">
                                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`hidden-placket`">
                                            <span class="checkbox-inner"></span>
                                            <div class="label-name">{{ frontBody.name }}</div>
                                        </label>
                                    </div>
                                    <div class="">
                                        <input class="hidden" type="radio" name="front-bdy-100" :id="`front-bdy-100`">
                                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`front-bdy-100`">
                                            <div class="font-bold text-center text-pink-ka text-xs print:text-base xl:text-sm uppercase tracking-wider">
                                                {{ currencyFormat(dataConfig.front_body.data.options.option_1[0].price) }}
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
                <div class="flex gap-4 *:w-full">
                    <div class="">
                        <div class="wrap-opt-cat">
                            <div class="cat-name">08. BUTTON</div>
                        </div>
                        <div class="flex justify-between border-pink-ka max-xl:mx-2 mt-4 max-xl:mb-4 ml-2 p-2 border">
                            <div v-for="(button, index) in dataConfig.button.data.options.option_1" :key="index">
                                <input :checked="formOption.button?.slug == button?.slug" @click.native="formOption.button = null" :value="button" class="hidden" type="radio" v-model="formOption.button" :id="`btn-${button.slug}`">
                                <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`btn-${button.slug}`">
                                    <span class="checkbox-inner"></span>
                                    <div class="label-name">{{ button.name }}</div>
                                </label>
                            </div>
                            <div class="flex font-roboto print-props">
                                <div ref="inputCont" class="box-input-wrapper" v-if="formOption.button?.buttonCode !== undefined && formOption.button?.buttonCode !== null">
                                    <input
                                        v-for="(digit, index) in split(formOption.button.buttonCode ?? '')"
                                        type="text"
                                        maxlength="1"
                                        :value="digit"
                                        class="box-input"
                                    >
                                </div>
                                <div v-else class="box-input-wrapper">
                                    <input
                                        v-for="(digit, index) in 4"
                                        type="text"
                                        maxlength="1"
                                        class="box-input"
                                    >
                                </div>
                            </div>
                            <div class="">
                                <input class="hidden" type="radio" name="button-100" :id="`button-100`">
                                <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`button-100`">
                                    <div class="font-bold text-center text-pink-ka text-xs print:text-base xl:text-sm uppercase tracking-wider">
                                        {{ currencyFormat(dataConfig.button.data.options.option_1[0].price) }}
                                    </div>
                                    <!-- <span class="checkbox-inner pink"></span> -->
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="wrap-opt-cat">
                            <div class="cat-name">09. BODY SNAP BUTTON</div>
                        </div>
                        <div class="flex justify-between border-pink-ka max-xl:mx-2 mt-4 mr-2 p-2 border">
                            <div v-for="bodySnapButton in dataConfig.body_snap_button.data.options.option_1" :key="bodySnapButton">
                                <input class="hidden" @click.native="formOption.bodySnapButton = null" :value="bodySnapButton" v-model="formOption.bodySnapButton" type="radio" :id="`${bodySnapButton.slug}`">
                                <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`${bodySnapButton.slug}`">
                                    <span class="checkbox-inner"></span>
                                    <div class="label-name">{{ bodySnapButton.name }}</div>
                                </label>
                            </div>
                            <div class="">
                                <input class="hidden" type="radio" name="bdy-snap-100" :id="`bdy-snap-50`">
                                <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`bdy-snap-50`">
                                    <div class="font-bold text-center text-pink-ka text-xs print:text-base xl:text-sm uppercase tracking-wider">
                                        {{ currencyFormat(dataConfig.body_snap_button.data.options.option_1[0].price) }}
                                    </div>
                                    <!-- <span class="checkbox-inner pink"></span> -->
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

                     <div v-for="(dataCleric, index) in dataConfig.cleric.data.options" :key="index"
                        class="space-y-4 border-pink-ka mx-2 mt-4 p-4 border">
                        <div v-for="(subCeleric, idx) in dataCleric" :key="idx" class="flex gap-2">
                            <div>
                                <input :checked="isChecked(subCeleric)" v-model="formOption.cleric"class="hidden" type="radio"  :value="subCeleric" :id="`${subCeleric.slug}`">
                                <label class="items-center gap-1 grid grid-cols-2 rounded h-full cursor-pointer" :for="`${subCeleric.slug}`">
                                    <div class="font-bold text-center text-primary-50 text-sm xl:text-sm uppercase tracking-wider">{{ subCeleric.no }}</div>
                                    <span class="checkbox-inner"></span>
                                </label>
                            </div>
                            <div class="flex gap-2 checkbox-cleric" :class="{ 'pointer-events-none': formOption.cleric?.slug != subCeleric.slug}">
                                <div v-for="(clericItems) in subCeleric.data">
                                    <div>
                                        <input :checked="isChecked(clericItems)" class="hidden" type="checkbox" :id="`cleric-${subCeleric.no}-${clericItems.slug}`">
                                        <label class="flex items-center gap-2 rounded h-full cursor-pointer" :for="`cleric-${subCeleric.no}-${clericItems.slug}`">
                                            <span class="checkbox-inner"></span>
                                            <div class="label-name">{{ clericItems.name }}</div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-self-end gap-2 self-end">
                            <div class="flex flex-col gap-1 col-span-2 mt-4 fabric-code">
                                    <label for="fabric-code" class="text-primary-50 uppercase tracking-wider whitespace-nowrap">fabric code (4 digit)</label>
                                    <div class="flex font-roboto print-props">
                                        <div ref="inputCont" class="box-input-wrapper" v-if="dataConfig.cleric.data.fabric[index]?.code?.length">
                                            <input
                                                v-for="(digit) in split(formOption.cleric.fabricCode)"
                                                type="text"
                                                maxlength="1"
                                                :value="digit"
                                                class="box-input"
                                            >
                                        </div>
                                        <div v-else class="box-input-wrapper">
                                            <input
                                                v-for="(digit) in 4"
                                                type="text"
                                                maxlength="1"
                                                class="box-input"
                                            >
                                        </div>
                                    </div>
                                </div>
                            <div class="self-end">
                                <input class="hidden" type="radio" name="cleric-50rb" :id="`cleric-50rb`">
                                <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`cleric-50rb`">
                                    <!-- <div class="font-bold text-center text-pink-ka text-xs print:text-base xl:text-sm uppercase tracking-wider">{{ currencyFormat(dataConfig[0].price) }}</div> -->
                                </label>
                            </div>
                        </div>
                    </div>
                 </div>

                 <div class="flex items-center gap-2 bg-secondary mx-2 mt-4 p-4 print-props">
                    <label for="fabric-code" class="text-primary-50 text-sm uppercase tracking-wider whitespace-nowrap">name</label>
                    <input type="text" :value="props.dataSemiCustom.name" class="block border-primary-50 p-2 border w-full h-8 font-roboto text-gray-900 text-sm">
                 </div>
            </div>

            <div>
                <!-- button hole, button thread, stitch thread -->
                <div class="gap-1 grid grid-cols-3 mt-0 whitespace-nowrap">
                   <!-- button hole -->
                   <div class="">
                       <div class="wrap-opt-cat">
                           <div class="font-bold text-lg text-white uppercase tracking-wider">11. button hole</div>
                       </div>

                       <div class="buttons-wrapper">
                           <div class="buttons-layout">
                               <div class="flex" v-for="(buttonHole, index) in dataConfig.button_hole.data.options.option_1" :key="index">
                                   <input :checked="formOption.buttonHole?.slug == buttonHole?.slug" @click.native="formOption.buttonHole = null" class="hidden" type="radio" name="button-hole" :value="buttonHole" v-model="formOption.buttonHole"  :id="`botton-hole-${buttonHole.slug}`">
                                   <label class="flex items-center gap-x-2 rounded h-full cursor-pointer" :for="`botton-hole-${buttonHole.slug}`">
                                       <span class="checkbox-inner"></span>
                                       <div class="label-name">{{ index + 1 }}. {{ buttonHole.name }}</div>
                                   </label>
                               </div>
                            </div>
    
                            <div class="button-price-label">
                               <div class="justify-self-end col-span-2 self-end">
                                   <input class="hidden" type="radio" name="button-hole-30rb" :id="`button-hole-30rb`">
                                   <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`button-hole-30rb`">
                                       <div class="font-bold text-center text-pink-ka text-xs print:text-base xl:text-sm uppercase tracking-wider">
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
                           <div class="font-bold text-lg text-white uppercase tracking-wider">12. button thread</div>
                       </div>
                       <div class="buttons-wrapper">
                            <div class="buttons-layout">
                                <div class="flex" v-for="(buttonThread, index) in dataConfig.button_thread.data.options.option_1" :key="index">
                                    <input :checked="formOption.buttonThread?.slug == buttonThread?.slug" @click.native="formOption.buttonThread = null" class="hidden" type="radio" name="button-thread" :value="buttonThread" v-model="formOption.buttonThread" :id="`botton-thread-${buttonThread.slug}`">
                                    <label class="flex items-center gap-2 rounded h-full cursor-pointer" :for="`botton-thread-${buttonThread.slug}`">
                                        <span class="checkbox-inner"></span>
                                        <div class="label-name">{{ index + 1 }}. {{ buttonThread.name }}</div>
                                    </label>
                                </div>
                            </div>

                           <div class="button-price-label">
                               <div class="justify-self-end col-span-2 self-end">
                                   <input class="hidden" type="radio" name="button-thread-30rb" :id="`button-thread-30rb`">
                                   <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`button-thread-30rb`">
                                       <div class="font-bold text-center text-pink-ka text-xs print:text-base xl:text-sm uppercase tracking-wider">
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
                           <div class="font-bold text-lg text-white uppercase tracking-wider">13. stitch thread</div>
                       </div>

                       <div class="buttons-wrapper">
                           <div class="buttons-layout">
                               <div class="flex" v-for="(stitchThread, index) in dataConfig.stitch_thread.data.options.option_1" :key="index">
                                   <input :checked="formOption.stitchThread?.slug == stitchThread?.slug" @click.native="formOption.stitchThread = null" class="hidden" type="radio" name="stitch-thread" :value="stitchThread" v-model="formOption.stitchThread" :id="`stitch-thread-${stitchThread.slug}`">
                                   <label class="flex items-center gap-2 rounded h-full cursor-pointer" :for="`stitch-thread-${stitchThread.slug}`">
                                       <span class="checkbox-inner"></span>
                                       <div class="label-name">{{ index + 1 }}. {{ stitchThread.name }}</div>
                                   </label>
                               </div>
                            </div>
                            <div class="button-price-label">
                               <div class="justify-self-end col-span-2 self-end">
                                   <input class="hidden" type="radio" name="stitch-thread-30rb" :id="`stitch-thread-30rb`">
                                   <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`stitch-thread-30rb`">
                                       <div class="font-bold text-center text-pink-ka text-xs print:text-base xl:text-sm uppercase tracking-wider">
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
                       <div class="font-bold text-lg text-white uppercase tracking-wider">14. embroidery</div>
                   </div>

                   <div class="space-y-4 border-pink-ka mx-2 mt-4 border">
                       <div class="grid grid-cols-3">
                           <!-- position -->
                           <div class="space-y-2 border-pink-ka p-2 border-r border-b border-b-dashed">
                               <div class="label-name">position</div>
                               <div class="flex" v-for="(embroideryPosition, index) in dataConfig.embroidery.data.options.position" :key="index">
                                   <input @click.native="formOption.embroidery.position = null" class="hidden"type="radio" name="embroidery-position" :value="embroideryPosition" v-model="formOption.embroidery.position" :id="`embroidery-position-${embroideryPosition.slug}`">
                                   <label class="flex items-center gap-1 rounded h-full cursor-pointer" :for="`embroidery-position-${embroideryPosition.slug}`">
                                       <span class="checkbox-inner"></span>
                                       <div class="label-name">{{ embroideryPosition.name }}</div>
                                   </label>
                               </div>
                           </div>

                           <!-- color -->
                           <div class="space-y-2 border-pink-ka p-2 border-r border-b border-b-dashed border-l">
                               <div class="label-name">color</div>
                               <div class="gap-1 grid grid-cols-2 grid-rows-5 grid-flow-col">
                                   <div class="flex" v-for="(embroideryColor, index) in dataConfig.embroidery.data.options.color" :key="index">
                                       <input @click.native="formOption.embroidery.color = null" class="hidden" type="radio" name="embroidery-color" :value="embroideryColor" v-model="formOption.embroidery.color" :id="`embroidery-color-${embroideryColor.slug}`">
                                       <label class="flex items-center gap-2 rounded h-full cursor-pointer" :for="`embroidery-color-${embroideryColor.slug}`">
                                           <span class="checkbox-inner"></span>
                                           <div class="label-name">{{ (index + 1)+'. '+ embroideryColor.name }}</div>
                                       </label>
                                   </div>
                               </div>
                           </div>

                           <!-- font type -->
                           <div class="space-y-2 border-pink-ka p-2 border-b border-b-dashed">
                               <div class="label-name">font type</div>
                               <div class="flex" v-for="(embroideryFontType, index) in dataConfig.embroidery.data.options.font_type" :key="index">
                                   <input @click.native="formOption.embroidery.fontType = null" class="hidden" type="radio" name="embroidery-font-type" :value="embroideryFontType" v-model="formOption.embroidery.fontType" :id="`embroidery-font-type-${embroideryFontType.slug}`">
                                   <label class="flex items-center gap-2 rounded h-full cursor-pointer" :for="`embroidery-font-type-${embroideryFontType.slug}`">
                                       <span class="checkbox-inner"></span>
                                       <div class="label-name">{{ +(index + 1)+'. '+embroideryFontType.name }}</div>
                                   </label>
                               </div>
                           </div>

                           <div class="col-span-3 p-4">
                               <div class="flex items-end gap-4">
                                   <div class="flex items-end font-roboto print-props-embroidery">
                                       <input v-model="formOption.embroidery.initialName.x" type="text" maxlength="1" class="block border-primary-50 p-2 border text-center text-gray-900 text-sm size-10">
                                       <input v-model="formOption.embroidery.initialName.dot" type="text" maxlength="1" class="block border-primary-50 border-y p-2 text-center text-gray-900 text-sm size-5">
                                       <input v-model="formOption.embroidery.initialName.y" type="text" maxlength="1" class="block border-primary-50 border-y p-2 border-r border-l text-center text-gray-900 text-sm size-10">
                                       <!-- <boxInput :digitCount="10" @update:input="onInputIntialName($event)"/> -->
                                       <div ref="inputCont" class="box-input-wrapper"
                                           v-if="formOption.embroidery.initialName.z !== undefined && formOption.embroidery.initialName.z !== null && formOption.embroidery.initialName.z !== ''">
                                           <input
                                               v-for="(digit, index) in split(formOption.embroidery.initialName.z)"
                                               type="text"
                                               maxlength="1"
                                               :value="digit"
                                               class="box-input"
                                           >
                                       </div>
                                       <div v-else class="box-input-wrapper">
                                           <input
                                               v-for="(digit, index) in 10"
                                               type="text"
                                               maxlength="1"
                                               class="box-input"
                                           >
                                       </div>
                                   </div>
                                   <div class="flex gap-4 w-full print-props-embroidery">
                                       <input type="text" v-model="formOption.embroidery.longName" class="block border-primary-50 p-2 border w-full h-8 font-roboto text-gray-900 text-sm print-props">
                                       <div class="whitespace-nowrap">
                                           <input class="hidden" type="radio" name="embroidery-50rb" :id="`embroidery-50rb`">
                                           <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`embroidery-50rb`">
                                               <div class="font-bold text-center text-pink-ka text-xs print:text-base xl:text-sm uppercase tracking-wider">{{ currencyFormat(dataConfig.embroidery.data.options.price) }}</div>
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
                       <div class="font-bold text-lg text-white uppercase tracking-wider">15. interlining</div>
                   </div>

                   <div class="flex justify-between items-center mx-2 mt-4">
                       <div class="border-pink-ka p-4 border lg:w-1/2">
                           <div class="flex" v-for="(interlining, index) in dataConfig.interlining.data.options.option_1" :key="index">
                               <input @click.native="formOption.interlining = null" class="hidden" type="radio" name="interlining" :value="interlining" v-model="formOption.interlining" :id="`interlining-${interlining.slug}`">
                               <label class="flex items-center gap-2 rounded h-full cursor-pointer" :for="`interlining-${interlining.slug}`">
                                   <span class="checkbox-inner"></span>
                                   <div class="label-name">{{ interlining.name }}</div>
                               </label>
                           </div>
                       </div>
                       <div class="flex justify-between items-center border-pink-ka p-4 border w-full">
                           <div v-for="(interlining, index) in dataConfig.interlining.data.options.option_2" :key="index">
                               <input @click.native="formOption.interlining = null" class="hidden" type="radio" name="interlining" :value="interlining" v-model="formOption.interlining" :id="`interlining-${interlining.slug}`">
                               <label class="flex items-center gap-2 rounded h-full cursor-pointer" :for="`interlining-${interlining.slug}`">
                                   <span class="checkbox-inner"></span>
                                   <div class="label-name">{{ interlining.name  }}</div>
                               </label>
                           </div>
                           <div class="whitespace-nowrap">
                               <input class="hidden" type="radio" name="interlining-100rb" :id="`interlining-100rb`">
                               <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`interlining-100rb`">
                                   <div class="font-bold text-center text-pink-ka text-xs print:text-base xl:text-sm uppercase tracking-wider">{{ currencyFormat(dataConfig.interlining.data.options.option_2[0].price) }} </div>
                                   <!-- <span class="checkbox-inner pink"></span> -->
                               </label>
                           </div>
                       </div>
                   </div>
               </div>

               <!-- sewing option -->
               <div class="mt-2">
                   <div class="wrap-opt-cat">
                       <div class="font-bold text-lg text-white uppercase tracking-wider">15. sewing option</div>
                   </div>

                   <div class="flex flex-col justify-between items-center mx-2 mt-4">
                       <div v-for="(sewing, index) in dataConfig.sewing_option.data.options" :key="index" class="flex justify-between items-center border-pink-ka p-4 border w-full">
                           <div v-for="(sewingOption, index) in sewing" :key="index">
                               <input @click.native="formOption.sewingOption = null" class="hidden" type="radio" name="sewing-option" :value="sewingOption" v-model="formOption.sewingOption" :id="`sewing-${sewingOption.slug}`">
                               <label class="flex items-center gap-2 rounded h-full cursor-pointer" :for="`sewing-${sewingOption.slug}`">
                                   <span class="checkbox-inner"></span>
                                   <div class="label-name">{{ sewingOption.name }}</div>
                               </label>
                           </div>
                           <div class="whitespace-nowrap">
                               <input class="hidden" type="radio" name="sewing-100rb" :id="`sewing-100rb`">
                               <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`sewing-100rb`">
                                   <div class="font-bold text-center text-pink-ka text-xs print:text-base xl:text-sm uppercase tracking-wider"> {{ currencyFormat(dataConfig.sewing_option.data.options[index][0].price) }}</div>
                                   <!-- <span class="checkbox-inner pink"></span> -->
                               </label>
                           </div>
                       </div>
                   </div>
               </div>

               <div class="mt-2">
                   <div class="wrap-opt-cat">
                       <div class="font-bold text-lg text-white uppercase tracking-wider">17. TAPE (INNER COLLAR STAND & LOWER PLACKET)</div>
                   </div>

                   <div class="flex justify-between items-center gap-4 border-pink-ka mx-2 mt-4 p-4 border">
                       <div class="flex font-roboto print-props">
                           <!-- <boxInput :digitCount="5" @update:input="onInputTape($event)"/> -->
                           <div ref="inputCont" class="box-input-wrapper" v-if="formOption.tape?.collar !== undefined && formOption.tape?.collar !== null && formOption.tape?.collar !== ''">
                               <input
                                   v-for="(digit, index) in split(formOption.tape.collar)"
                                   type="text"
                                   maxlength="1"
                                   :value="digit"
                                   class="box-input"
                               >
                           </div>
                           <div v-else class="box-input-wrapper">
                               <input
                                   v-for="(digit, index) in 5"
                                   type="text"
                                   maxlength="1"
                                   class="box-input"
                               >
                           </div>
                       </div>
                       <div class="w-9/12 print-props">
                           <input type="text" v-model="formOption.tape.lower" class="block border-primary-50 p-2 border w-full h-8 font-roboto text-gray-900 text-sm">
                       </div>
                       <div class="whitespace-nowrap">
                           <input class="hidden" type="radio" name="sewing-200rb" :id="`sewing-200rb`">
                           <label class="flex items-center gap-4 px-2 rounded h-full font-roboto cursor-pointer" :for="`sewing-200rb`">
                               <div class="font-bold text-center text-pink-ka text-xs print:text-base xl:text-sm uppercase tracking-wider"> {{ currencyFormat(formOption.tape.price ?? 200000)  }} </div>
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
                            <textarea class="border-primary-50 p-2 border w-full h-full font-roboto placeholder:font-josefin placeholder:tracking-widest placeholder-primary-50" v-model="additionalNote" name="" id="" placeholder="NOTE"></textarea>
                        </div>
                        <div class="col-span-6">
                            <table class="w-full text-primary-50 text-sm uppercase">
                                <thead>
                                    <tr class="*:border-primary-50 *:border *:text-center">
                                        <th colspan="2">option</th>
                                        <th>quantity</th>
                                        <th>sub total</th>
                                    </tr>
                                </thead>
                                <tbody class="print-props-md">
                                    <tr class="*:border-primary-50 *:px-2 *:pt-2 *:pb-1 *:border *:text-center">
                                        <td>a</td>
                                        <td>30.000</td>
                                        <td>{{ dataPriceTable?.[30000]?.quantity }}</td>
                                        <td class="!text-left">{{ currencyFormat(dataPriceTable?.[30000]?.subTotal) }}</td>
                                    </tr>
                                    <tr class="*:border-primary-50 *:px-2 *:pt-2 *:pb-1 *:border *:text-center">
                                        <td>b</td>
                                        <td>50.000</td>
                                        <td>{{ dataPriceTable?.[50000]?.quantity }}</td>
                                        <td class="!text-left">{{ currencyFormat(dataPriceTable?.[50000]?.subTotal) }}</td>
                                    </tr>
                                    <tr class="*:border-primary-50 *:px-2 *:pt-2 *:pb-1 *:border *:text-center">
                                        <td>c</td>
                                        <td>70.000</td>
                                        <td>{{ dataPriceTable?.[70000]?.quantity }}</td>
                                        <td class="!text-left">{{ currencyFormat(dataPriceTable?.[70000]?.subTotal) }}</td>
                                    </tr>
                                    <tr class="*:border-primary-50 *:px-2 *:pt-2 *:pb-1 *:border *:text-center">
                                        <td>d</td>
                                        <td>100.000</td>
                                        <td>{{ dataPriceTable?.[100000]?.quantity }}</td>
                                        <td class="!text-left">{{ currencyFormat(dataPriceTable?.[100000]?.subTotal) }}</td>
                                    </tr>
                                    <tr class="*:border-primary-50 *:px-2 *:pt-2 *:pb-1 *:border *:text-center">
                                        <td>e</td>
                                        <td>200.000</td>
                                        <td>{{ dataPriceTable?.[200000]?.quantity }}</td>
                                        <td class="!text-left">{{ currencyFormat(dataPriceTable?.[200000]?.subTotal) }}</td>
                                    </tr>
                                    <tr class="*:border-primary-50 *:px-2 *:pt-2 *:pb-1 *:border *:text-center">
                                        <td colspan="3">option total</td>
                                        <td>{{ currencyFormat(dataPriceTable?.optionTotal) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- <div class="col-span-3">
                            <div class="col-span-3">
                                <textarea class="border-primary-50 p-2 border w-full h-full font-roboto placeholder:font-josefin placeholder:tracking-widest placeholder-primary-50" v-model="additionalNote" name="" id="" placeholder="NOTE"></textarea>
                            </div>
                            <div class="space-y-2 col-span-2">
                                <div>
                                    <small>Gift Card</small>
                                    <VueNumber v-model="discount" v-bind="number_input"
                                            class="border-pink-ka px-4 pt-2 pb-1 border w-full text-primary-50 number-input"></VueNumber>
                                </div>
                                <div>
                                    <small>Option Price</small>
                                    <VueNumber v-model.lazy="price" v-bind="number_input" class="border-pink-ka px-4 pt-2 pb-1 border w-full text-primary-50 number-input"></VueNumber>
                                </div>
                                <div class="print:hidden">
                                    <button class="bg-secondary px-5 pt-3 pb-2 w-full text-center text-pink-ka">APPLY PRICE</button>
                                </div>
                            </div>
                        </div> -->
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
        @apply font-bold text-center text-primary-50 text-xs print:text-lg uppercase tracking-wider;
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
