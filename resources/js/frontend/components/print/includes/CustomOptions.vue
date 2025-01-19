<script setup>
    import { isNull } from 'lodash';
    import { ref, defineProps, watch, defineExpose, computed, onMounted } from 'vue'

    const props = defineProps({
        dataSemiCustom: Object,
        dataConfig: Object
    });

    const formOption = ref(props.dataSemiCustom.option_form);
    const additionalNote = ref(props.dataSemiCustom.option_note);
    const price = ref(props.dataSemiCustom.option_total);
    const discount = ref(props.dataSemiCustom.option_discount);


   onMounted(() => {
        const base = [];
        const cleric = [];
        for (let c in props.dataConfig.cleric.data.options) {
            base.push(props.dataConfig.cleric.data.options[c])
            for (let d in props.dataConfig.cleric.data.options[c]) {
                cleric.push(props.dataConfig.cleric.data.options[c][d].data)
            }
        }
        console.log(base, cleric);

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
</script>

<template>
    <div class="relative min-h-svh mb-28">
        <!-- collar -->
        <div id="collar">
            <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                <div class="text-xl font-bold tracking-wider text-white uppercase">02. Collar</div>
            </div>

            <div class="grid grid-cols-6 gap-2 my-10 px-14 whitespace-nowrap">
                <div class="col-span-6 pt-2 xl:col-span-2">
                    <div class="flex" v-for="(collar, index) in dataConfig.collar.data.options.option_1" :key="index">
                        <input :checked="collar?.slug === formOption.collar?.slug" @click.native="formOption.collar = null" class="hidden" v-model="formOption.collar" :value="collar" type="radio" :id="'collar-'+collar.slug">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="'collar-'+collar.slug">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ collar.name }}</div>
                        </label>
                    </div>
                    <div class="mt-2 mb-3 text-xs text-pink-ka font-roboto">*No Additional Charge for above items</div>
                </div>
                <div class="col-span-6 xl:col-span-4">
                    <div class="grid grid-cols-1 p-2 border 2xl:grid-cols-2 border-pink-ka">
                        <div class="flex" v-for="(collar50, index) in dataConfig.collar.data.options.option_2" :key="index">
                            <input :checked="collar50?.slug === formOption.collar?.slug" @click.native="formOption.collar = null" class="hidden" type="radio" v-model="formOption.collar" :value="collar50" :id="'collar-'+collar50.slug">
                            <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="'collar-'+collar50.slug">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ collar50.name }}</div>
                            </label>
                        </div>
                        <div class="self-end col-span-2 justify-items-end">
                            <div class="">
                                <input class="hidden" type="radio" name="collar-50" :id="`50rb`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`50rb`">
                                    <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka">
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
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`collar-${collar100.slug}`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ collar100.name }}</div>
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center col-span-2 mt-4 lg:gap-4 fabric-code max-xl:flex-wrap">
                            <label for="fabric-code" class="tracking-wider uppercase text-primary-50 whitespace-nowrap">fabric code (4 digit)</label>
                            <div class="flex font-roboto">
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
                        <div class="self-end col-3 justify-items-end">
                            <div class="">
                                <input class="hidden" type="radio" name="collar-100" :id="`100rb`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`100rb`">
                                    <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka">
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
            <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                <div class="text-xl font-bold tracking-wider text-white uppercase">03. cuffs</div>
            </div>

            <div class="grid grid-cols-6 gap-2 my-10 px-14 whitespace-nowrap">
                <div class="col-span-6 pt-2 xl:col-span-2">
                    <div class="flex" v-for="(cuffs, index) in dataConfig.cuffs.data.options.option_1" :key="index">
                        <input :checked="formOption.cuffs?.slug == cuffs?.slug" @click.native="formOption.cuffs = null" class="hidden" type="radio" name="cuffs" v-model="formOption.cuffs" :value="cuffs" :id="`cuffs-${cuffs.slug}`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cuffs-${cuffs.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ cuffs.name }}</div>
                        </label>
                    </div>
                    <div class="mt-4 text-xs text-pink-ka font-roboto">*No Additional Charge for above items</div>
                </div>
                <div class="col-span-6 xl:col-span-4">
                    <div>
                        <div class="grid grid-cols-1 gap-4 p-2 border 2xl:grid-cols-2 border-pink-ka">
                            <div class="flex" v-for="(cuffs, index) in dataConfig.cuffs.data.options.option_2" :key="index">
                                <input :checked="formOption.cuffs?.slug == cuffs?.slug" @click.native="formOption.cuffs = null" class="hidden" v-model="formOption.cuffs" :value="cuffs" type="radio" name="cuffs" :id="`cuffs-${cuffs.slug}`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cuffs-${cuffs.slug}`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ cuffs.name }}</div>
                                </label>
                            </div>
                            <div class="self-end col-3 justify-items-end">
                                <div class="">
                                    <input class="hidden" type="radio" name="cuffs-70" :id="`cuffs-70rb`">
                                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cuffs-70rb`">
                                        <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka">
                                            {{ currencyFormat(dataConfig.cuffs.data.options.option_2[0].price) }}
                                        </div>
                                        <!-- <span class="checkbox-inner pink"></span> -->
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 p-2 border border-pink-ka">
                            <div class="col-span-3">
                                <div class="flex" v-for="(cuffs, index) in dataConfig.cuffs.data.options.option_3" :key="index">
                                    <input :checked="formOption.cuffs?.slug == cuffs?.slug" @click.native="formOption.cuffs = null" class="hidden" v-model="formOption.cuffs" :value="cuffs" type="radio" name="cuffs" :id="`cuffs-${cuffs.slug}`">
                                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cuffs-${cuffs.slug}`">
                                        <span class="checkbox-inner"></span>
                                        <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ cuffs.name }}</div>
                                    </label>
                                </div>
                            </div>
                            <div class="flex items-center col-span-2 mt-4 lg:gap-8 fabric-code max-lg:flex-wrap">
                                <label for="fabric-code" class="tracking-wider uppercase text-primary-50 whitespace-nowrap">fabric code (4 digit)</label>
                                <div class="flex font-roboto">
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
                            <div class="self-end cols-3 justify-items-end">
                                <div class="">
                                    <input class="hidden" type="radio" name="cuffs-100" :id="`cuffs-100rb`">
                                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cuffs-100rb`">
                                        <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka">
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
                        <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                            <div class="text-xl font-bold tracking-wider text-white uppercase">04. FRONT BODY</div>
                        </div>
                        <div class="flex justify-between p-2 mt-4 border border-pink-ka">
                            <div class="flex" v-for="(frontBody, index) in dataConfig.front_body.data.options.option_1" :key="index">
                                <input :checked="formOption.cuffs?.slug == frontBody?.slug" @click.native="formOption.frontBody = null" class="hidden" type="radio" name="front-body" :value="frontBody" v-model="formOption.frontBody"  :id="`hidden-placket`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`hidden-placket`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ frontBody.name }}</div>
                                </label>
                            </div>
                            <div class="">
                                <input class="hidden" type="radio" name="front-bdy-100" :id="`front-bdy-100`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`front-bdy-100`">
                                    <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka">
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
        <div class="xl:flex *:w-full gap-4">
            <div class="">
                <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                    <div class="text-xl font-bold tracking-wider text-white uppercase">08. BUTTON</div>
                </div>
                <div class="flex justify-between p-2 mt-4 border border-pink-ka ml-14 max-xl:mx-6 max-xl:mb-4">
                    <div v-for="(button, index) in dataConfig.button.data.options.option_1" :key="index">
                        <input :checked="formOption.button?.slug == button?.slug" @click.native="formOption.button = null" :value="button" class="hidden" type="radio" v-model="formOption.button" :id="`btn-${button.slug}`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`btn-${button.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ button.name }}</div>
                        </label>
                    </div>
                    <div class="flex font-roboto">
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
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`button-100`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka">
                                {{ currencyFormat(dataConfig.button.data.options.option_1[0].price) }}
                            </div>
                            <!-- <span class="checkbox-inner pink"></span> -->
                        </label>
                    </div>
                </div>
            </div>
            <div class="">
                <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                    <div class="text-xl font-bold tracking-wider text-white uppercase">09. BODY SNAP BUTTON</div>
                </div>
                <div class="flex justify-between p-2 mt-4 border border-pink-ka mr-14 max-xl:mx-6">
                    <div v-for="bodySnapButton in dataConfig.body_snap_button.data.options.option_1" :key="bodySnapButton">
                        <input class="hidden" @click.native="formOption.bodySnapButton = null" :value="bodySnapButton" v-model="formOption.bodySnapButton" type="radio" :id="`${bodySnapButton.slug}`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`${bodySnapButton.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ bodySnapButton.name }}</div>
                        </label>
                    </div>
                    <div class="">
                        <input class="hidden" type="radio" name="bdy-snap-100" :id="`bdy-snap-50`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`bdy-snap-50`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka">
                                {{ currencyFormat(dataConfig.body_snap_button.data.options.option_1[0].price) }}
                            </div>
                            <!-- <span class="checkbox-inner pink"></span> -->
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- cleric -->
        <div class="mt-4">
             <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                 <div class="text-xl font-bold tracking-wider text-white uppercase">10. Cleric</div>
             </div>

             <div v-for="(dataCleric, index) in dataConfig.cleric.data.options" :key="index"
                class="p-4 mx-6 mt-4 space-y-4 border xl:mx-14 border-pink-ka">
                <div v-for="(subCeleric, index) in dataCleric" :key="index" class="flex gap-2">
                    <div>
                        <input :checked="formOption.cleric?.slug == subCeleric?.slug" v-model="formOption.cleric"class="hidden" type="radio"  :value="subCeleric" :id="`${subCeleric.slug}`">
                        <label class="grid items-center h-full grid-cols-2 gap-1 rounded cursor-pointer" :for="`${subCeleric.slug}`">
                            <div class="text-sm font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ subCeleric.no }}</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="flex gap-2 checkbox-cleric" :class="{ 'pointer-events-none': formOption.cleric?.slug != subCeleric.slug}">
                        <div v-for="(clericItems, index) in subCeleric.data">
                            <!-- <div v-for="(itemsCeleric, index) in formOption.cleric?.data"> -->
                                <input :checked="formOption.cleric?.slug == clericItems.slug" class="hidden" type="checkbox" :id="`cleric-${subCeleric.no}-${clericItems.slug}`">
                                <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`cleric-${subCeleric.no}-${clericItems.slug}`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ clericItems.name }}</div>
                                </label>
                            <!-- </div> -->
                        </div>
                    </div>
                </div>

                <div class="flex self-end gap-2 justify-self-end">
                    <div class="flex flex-col col-span-2 gap-1 mt-4 fabric-code">
                            <label for="fabric-code" class="tracking-wider uppercase text-primary-50 whitespace-nowrap">fabric code (4 digit)</label>
                            <div class="flex font-roboto">
                                <!-- <boxInput :digitCount="4" @update:input="onInputBox($event, 'cleric', 'fabricCode')"/> -->
                            </div>
                        </div>
                    <div class="self-end">
                        <input class="hidden" type="radio" name="cleric-50rb" :id="`cleric-50rb`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cleric-50rb`">
                            <!-- <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka">{{ currencyFormat(dataConfig[0].price) }}</div> -->
                        </label>
                    </div>
                </div>
            </div>
         </div>

         <!-- button hole, button thread, stitch thread -->
         <div class="grid gap-2 mt-4 lg:grid-cols-2 xl:grid-cols-3 whitespace-nowrap">
            <!-- button hole -->
            <div class="max-lg:col-span-2">
                <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                    <div class="text-lg font-bold tracking-wider text-white uppercase">11. button hole</div>
                </div>

                <div class="grid grid-cols-2 gap-2 p-4 mx-6 mt-4 border lg:ml-6 xl:ml-14 lg:mr-0 border-pink-ka">
                    <div class="flex" v-for="(buttonHole, index) in dataConfig.button_hole.data.options.option_1" :key="index">
                        <input :checked="formOption.buttonHole?.slug == buttonHole?.slug" @click.native="formOption.buttonHole = null" class="hidden" type="radio" name="button-hole" :value="buttonHole" v-model="formOption.buttonHole"  :id="`botton-hole-${buttonHole.slug}`">
                        <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`botton-hole-${buttonHole.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ index + 1 }}. {{ buttonHole.name }}</div>
                        </label>
                    </div>

                    <div class="self-end col-span-2 justify-self-end">
                        <input class="hidden" type="radio" name="button-hole-30rb" :id="`button-hole-30rb`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`button-hole-30rb`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka">
                                {{ currencyFormat(dataConfig.button_hole.data.options.option_1[0].price) }}
                            </div>
                            <!-- <span class="checkbox-inner pink"></span> -->
                        </label>
                    </div>
                </div>
            </div>

            <!-- button thread -->
            <div class="max-lg:col-span-2">
                <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                    <div class="text-lg font-bold tracking-wider text-white uppercase">12. button thread</div>
                </div>

                <div class="grid grid-cols-2 gap-2 p-4 mx-6 mt-4 border lg:mr-6 lg:ml-0 xl:mx-0 border-pink-ka">
                    <div class="flex" v-for="(buttonThread, index) in dataConfig.button_thread.data.options.option_1" :key="index">
                        <input :checked="formOption.buttonThread?.slug == buttonThread?.slug" @click.native="formOption.buttonThread = null" class="hidden" type="radio" name="button-thread" :value="buttonThread" v-model="formOption.buttonThread" :id="`botton-thread-${buttonThread.slug}`">
                        <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`botton-thread-${buttonThread.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ index + 1 }}. {{ buttonThread.name }}</div>
                        </label>
                    </div>

                    <div class="self-end col-span-2 justify-self-end">
                        <input class="hidden" type="radio" name="button-thread-30rb" :id="`button-thread-30rb`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`button-thread-30rb`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka">
                                {{ currencyFormat(dataConfig.button_thread.data.options.option_1[0].price) }}
                            </div>
                            <!-- <span class="checkbox-inner pink"></span> -->
                        </label>
                    </div>
                </div>
            </div>

            <!-- stitch thread -->
            <div class="max-xl:col-span-2">
                <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                    <div class="text-lg font-bold tracking-wider text-white uppercase">13. stitch thread</div>
                </div>

                <div class="grid grid-cols-2 gap-2 p-4 mx-6 mt-4 border xl:mr-14 xl:ml-0 border-pink-ka">
                    <div class="flex" v-for="(stitchThread, index) in dataConfig.stitch_thread.data.options.option_1" :key="index">
                        <input :checked="formOption.stitchThread?.slug == stitchThread?.slug" @click.native="formOption.stitchThread = null" class="hidden" type="radio" name="stitch-thread" :value="stitchThread" v-model="formOption.stitchThread" :id="`stitch-thread-${stitchThread.slug}`">
                        <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`stitch-thread-${stitchThread.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ index + 1 }}. {{ stitchThread.name }}</div>
                        </label>
                    </div>

                    <div class="self-end col-span-2 justify-self-end">
                        <input class="hidden" type="radio" name="stitch-thread-30rb" :id="`stitch-thread-30rb`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`stitch-thread-30rb`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka">
                                {{ currencyFormat(dataConfig.stitch_thread.data.options.option_1[0].price) }}
                            </div>
                            <!-- <span class="checkbox-inner pink"></span> -->
                        </label>
                    </div>
                </div>
            </div>
         </div>

         <!-- embroidery -->
         <div class="mt-6">
            <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                <div class="text-lg font-bold tracking-wider text-white uppercase">14. embroidery</div>
            </div>

            <div class="mx-6 mt-4 space-y-4 border xl:mx-14 border-pink-ka">
                <div class="grid grid-cols-3">
                    <!-- position -->
                    <div class="p-4 space-y-4 border-b border-r border-b-dashed border-pink-ka max-xl:col-span-3">
                        <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">position</div>
                        <div class="flex" v-for="(embroideryPosition, index) in dataConfig.embroidery.data.options.position" :key="index">
                            <input @click.native="formOption.embroidery.position = null" class="hidden"type="radio" name="embroidery-position" :value="embroideryPosition" v-model="formOption.embroidery.position" :id="`embroidery-position-${embroideryPosition.slug}`">
                            <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`embroidery-position-${embroideryPosition.slug}`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ embroideryPosition.name }}</div>
                            </label>
                        </div>
                    </div>

                    <!-- color -->
                    <div class="p-4 space-y-4 border-b border-r border-b-dashed border-pink-ka max-xl:col-span-3">
                        <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">color</div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="flex" v-for="(embroideryColor, index) in dataConfig.embroidery.data.options.color" :key="index">
                                <input @click.native="formOption.embroidery.color = null" class="hidden" type="radio" name="embroidery-color" :value="embroideryColor" v-model="formOption.embroidery.color" :id="`embroidery-color-${embroideryColor.slug}`">
                                <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`embroidery-color-${embroideryColor.slug}`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ (index + 1)+'. '+ embroideryColor.name }}</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- font type -->
                    <div class="p-4 space-y-4 border-b border-b-dashed border-pink-ka max-xl:col-span-3">
                        <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">font type</div>
                        <div class="flex" v-for="(embroideryFontType, index) in dataConfig.embroidery.data.options.font_type" :key="index">
                            <input @click.native="formOption.embroidery.fontType = null" class="hidden" type="radio" name="embroidery-font-type" :value="embroideryFontType" v-model="formOption.embroidery.fontType" :id="`embroidery-font-type-${embroideryFontType.slug}`">
                            <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`embroidery-font-type-${embroideryFontType.slug}`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ +(index + 1)+'. '+embroideryFontType.name }}</div>
                            </label>
                        </div>
                    </div>

                    <div class="col-span-3 p-4">
                        <div class="flex items-end gap-4 max-xl:flex-wrap">
                            <div class="flex items-end font-roboto">
                                <input v-model="formOption.embroidery.initialName.x" type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border size-10 border-primary-50">
                                <input v-model="formOption.embroidery.initialName.dot" type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 size-5 border-y border-primary-50">
                                <input v-model="formOption.embroidery.initialName.y" type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-l border-r size-10 border-y border-primary-50">
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
                            <div class="flex w-full gap-4">
                                <input type="text" v-model="formOption.embroidery.longName" class="block w-full h-8 p-2 text-sm text-gray-900 border border-primary-50 font-roboto">
                                <div class="whitespace-nowrap">
                                    <input class="hidden" type="radio" name="embroidery-50rb" :id="`embroidery-50rb`">
                                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`embroidery-50rb`">
                                        <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka">{{ currencyFormat(dataConfig.embroidery.data.options.price) }}</div>
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

         <!-- interlining -->
         <div class="mt-6">
            <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                <div class="text-lg font-bold tracking-wider text-white uppercase">15. interlining</div>
            </div>

            <div class="flex items-center justify-between mx-6 mt-4 xl:mx-14">
                <div class="p-4 border lg:w-1/2 border-pink-ka">
                    <div class="flex" v-for="(interlining, index) in dataConfig.interlining.data.options.option_1" :key="index">
                        <input @click.native="formOption.interlining = null" class="hidden" type="radio" name="interlining" :value="interlining" v-model="formOption.interlining" :id="`interlining-${interlining.slug}`">
                        <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`interlining-${interlining.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ interlining.name }}</div>
                        </label>
                    </div>
                </div>
                <div class="flex items-center justify-between w-full p-4 border border-pink-ka">
                    <div v-for="(interlining, index) in dataConfig.interlining.data.options.option_2" :key="index">
                        <input @click.native="formOption.interlining = null" class="hidden" type="radio" name="interlining" :value="interlining" v-model="formOption.interlining" :id="`interlining-${interlining.slug}`">
                        <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`interlining-${interlining.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ interlining.name  }}</div>
                        </label>
                    </div>
                    <div class="whitespace-nowrap">
                        <input class="hidden" type="radio" name="interlining-100rb" :id="`interlining-100rb`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`interlining-100rb`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka">{{ currencyFormat(dataConfig.interlining.data.options.option_2[0].price) }} </div>
                            <!-- <span class="checkbox-inner pink"></span> -->
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- sewing option -->
        <div class="mt-6">
            <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                <div class="text-lg font-bold tracking-wider text-white uppercase">15. sewing option</div>
            </div>

            <div class="flex flex-col items-center justify-between mx-6 mt-4 xl:mx-14">
                <div v-for="(sewing, index) in dataConfig.sewing_option.data.options" :key="index" class="flex items-center justify-between w-full p-4 border border-pink-ka">
                    <div v-for="(sewingOption, index) in sewing" :key="index">
                        <input @click.native="formOption.sewingOption = null" class="hidden" type="radio" name="sewing-option" :value="sewingOption" v-model="formOption.sewingOption" :id="`sewing-${sewingOption.slug}`">
                        <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`sewing-${sewingOption.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ sewingOption.name }}</div>
                        </label>
                    </div>
                    <div class="whitespace-nowrap">
                        <input class="hidden" type="radio" name="sewing-100rb" :id="`sewing-100rb`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`sewing-100rb`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka"> {{ currencyFormat(dataConfig.sewing_option.data.options[index][0].price) }}</div>
                            <!-- <span class="checkbox-inner pink"></span> -->
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                <div class="text-lg font-bold tracking-wider text-white uppercase">17. TAPE (INNER COLLAR STAND & LOWER PLACKET)</div>
            </div>

            <div class="flex items-center justify-between gap-4 p-4 mx-6 mt-4 border xl:mx-14 border-pink-ka">
                <div class="flex font-roboto">
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
                <div class="w-9/12">
                    <input type="text" v-model="formOption.tape.lower" class="block w-full h-8 p-2 text-sm text-gray-900 border border-primary-50 font-roboto">
                </div>
                <div class="whitespace-nowrap">
                    <input class="hidden" type="radio" name="sewing-200rb" :id="`sewing-200rb`">
                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer font-roboto" :for="`sewing-200rb`">
                        <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka"> {{ currencyFormat(formOption.tape.price ?? 200000)  }} </div>
                        <!-- <span class="checkbox-inner pink"></span> -->
                    </label>
                </div>
            </div>
        </div>

        <div class="mt-6 mb-32">
            <div class="flex items-center justify-between px-4 py-2 lg:px-14 bg-blue-ka">
                <div class="font-bold tracking-widest text-white uppercase lg:text-xl">ADDITIONAL NOTES</div>
            </div>
            <div class="grid grid-cols-5 gap-3 px-6 my-10 xl:px-14">
                <div class="col-span-3">
                    <textarea class="w-full h-full p-2 border placeholder:font-josefin font-roboto border-primary-50 placeholder-primary-50 placeholder:tracking-widest" v-model="additionalNote" name="" id="" placeholder="NOTE"></textarea>
                </div>
                <div class="col-span-2 space-y-2">
                    <input v-model="discount" type="number"
                         class="w-full px-4 pt-2 pb-1 border number-input border-pink-ka text-primary-50" placeholder="DISCOUNT"/>
                    <input v-model="price" type="number" class="w-full px-4 pt-2 pb-1 border number-input border-pink-ka text-primary-50" placeholder="RP" />
                    <div>
                        <button class="w-full px-5 pt-3 pb-2 text-center text-pink-ka bg-secondary">APPLY PRICE</button>
                    </div>
                </div>
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
        @apply bg-primary-50 border-primary-50;
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
        @apply bg-primary-50 border-primary-50;
        color: #fff;
        background-image: url("data:image/svg+xml,%3C%3Fxml version='1.0' encoding='UTF-8'%3F%3E%3Csvg width='14px' height='10px' viewBox='0 0 14 10' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'%3E%3C!-- Generator: Sketch 59.1 (86144) - https://sketch.com --%3E%3Ctitle%3Echeck%3C/title%3E%3Cdesc%3ECreated with Sketch.%3C/desc%3E%3Cg id='Page-1' stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'%3E%3Cg id='ios_modification' transform='translate(-27.000000, -191.000000)' fill='%23FFFFFF' fill-rule='nonzero'%3E%3Cg id='Group-Copy' transform='translate(0.000000, 164.000000)'%3E%3Cg id='ic-check-18px' transform='translate(25.000000, 23.000000)'%3E%3Cpolygon id='check' points='6.61 11.89 3.5 8.78 2.44 9.84 6.61 14 15.56 5.05 14.5 4'%3E%3C/polygon%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        background-size: 14px 10px;

        &.pink {
            @apply border-pink-ka bg-pink-ka;
        }
    }

    .checkbox-inner {
        @apply flex items-center justify-center text-transparent border size-7 border-primary-50;
        background: transparent no-repeat center;

        &.pink {
            @apply border-pink-ka;
        }
    }
</style>
