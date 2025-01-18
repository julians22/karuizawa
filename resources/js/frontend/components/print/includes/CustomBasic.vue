<script setup>
    import { ref, watch, defineEmits, computed } from 'vue';
    import boxInput from '@frontend/components/utils/fields/boxInput.vue';
    import { useProducts } from '@frontend//store/product';


    const props = defineProps({
        dataSemiCustom: Object,
        dataConfig: Object
    });

    console.log(props.dataSemiCustom);

    const emitFrom = defineEmits({
        'form-custom-shirt': 'form-custom-shirt',
        'additional-basic': 'additional-basic',
    });

    const form = ref(
        props.dataSemiCustom.basic_form
    );

    const formSize = ref(props.dataSemiCustom.size);
    const additionalNote = ref(props.dataSemiCustom.base_note);
    const price = ref(props.dataSemiCustom.base_price);
    const discount = ref(props.dataSemiCustom.base_discount);

    const split = (string) => {
        return string.split('');
    }
</script>

<template>
    <div class="relative min-h-svh">
        <div class="flex items-center justify-between p-6 bg-primary-50 lg:px-14 lg:py-7">
            <div class="text-lg font-bold tracking-widest text-white uppercase lg:text-xl">CUSTOM MADE SHIRT</div>
        </div>

        <!-- fabric code -->
        <div>
            <div class="flex items-center justify-between px-4 py-2 bg-primary-300 lg:px-14">
                <div class="font-bold tracking-widest text-white uppercase lg:text-xl">01. FABRIC</div>
            </div>
            <div class="flex items-center gap-8 px-6 my-5 lg:px-10 xl:px-14 fabric-code">
                <label for="fabric-code" class="tracking-widest uppercase text-primary-50 lg:whitespace-pre-wrap">fabric code</label>
                <div ref="inputCont" class="box-input-wrapper">
                    <input
                        v-for="(digit, index) in split(form.fabric.fabricCode)"
                        type="text"
                        maxlength="1"
                        :value="digit"
                        class="box-input"
                    >
                </div>
                <!-- <boxInput :digitCount="4" @update:input="onInputBox($event, 'fabric', 'fabricCode')"/> -->
                <input v-model="form.fabric.text" type="text" class="block w-full h-8 p-2 text-sm text-gray-900 border border-r border-primary-50 font-roboto">
            </div>
        </div>

        <!-- collar -->
        <div>
            <div class="flex items-center justify-between px-4 py-2 bg-primary-300 lg:px-14">
                <div class="font-bold tracking-widest text-white uppercase lg:text-xl">02. COLLAR</div>
            </div>
            <div class="grid grid-cols-6 px-6 my-10 lg:px-10 xl:px-14">
                <div v-for="collar in props.dataConfig.collar.data.basic">
                    <input @click.native="form.collar = null" class="hidden" type="radio" v-model="form.collar" :value="collar" name="collar"  :id="'collar-' + collar.slug">
                    <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="'collar-' + collar.slug">
                        <img class="h-24" :src="'/'+collar.image" alt="">
                        <div class="text-xs font-bold tracking-widest text-center uppercase text-primary-50 2xl:text-lg xl:text-base">{{ collar.name }}</div>
                        <span class="checkbox-inner"></span>
                    </label>
                </div>
            </div>
            <div class="flex items-center gap-12 mx-20 my-10">
                <div class="text-xs font-bold tracking-widest uppercase text-primary-50 2xl:text-lg xl:text-base">OPTION NUMBER</div>
                <div class="flex font-roboto">
                    <div v-if="form.collar?.optionNumber" ref="inputCont" class="box-input-wrapper">
                        <input
                            v-for="(digit, index) in split(form.collar?.optionNumber)"
                            type="text"
                            maxlength="1"
                            :value="digit"
                            class="box-input"
                        >
                    </div>
                    <div v-else class="box-input-wrapper">
                        <input
                            v-for="(digit, index) in 2"
                            type="text"
                            maxlength="1"
                            class="box-input"
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- cuffs and  front body -->
        <div class="grid grid-cols-2 gap-4">
            <!-- cuffs -->
            <div>
                <div class="flex items-center justify-between px-4 py-2 bg-primary-300 lg:px-14">
                    <div class="font-bold tracking-widest text-white uppercase lg:text-xl">03. CUFFS</div>
                </div>
                <div class="grid grid-cols-3 px-6 my-10 lg:px-10 xl:px-14">
                    <div v-for="cuff in props.dataConfig.cuffs.data.basic">
                        <input @click.native="form.cuff = null" class="hidden" type="radio" v-model="form.cuff" name="cuff" :value="cuff" :id="'cuff-' + cuff.slug">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="'cuff-' + cuff.slug">
                            <img class="h-28" :src="'/'+cuff.image" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase text-primary-50 2xl:text-lg xl:text-base">{{ cuff.name }}</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                </div>
                <div class="flex items-center gap-12 px-6 my-10 xl:mx-20">
                    <div class="text-xs font-bold tracking-widest uppercase text-primary-50 2xl:text-lg xl:text-base">OPTION NUMBER</div>
                    <div class="flex font-roboto">
                        <div v-if="form.cuff?.optionNumber" ref="inputCont" class="box-input-wrapper">
                            <input
                                v-for="(digit, index) in split(form.cuff?.optionNumber)"
                                type="text"
                                maxlength="1"
                                :value="digit"
                                class="box-input"
                            >
                        </div>
                        <div v-else class="box-input-wrapper">
                            <input
                                v-for="(digit, index) in 2"
                                type="text"
                                maxlength="1"
                                class="box-input"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- front body -->
            <div>
                <div class="flex items-center justify-between px-4 py-2 bg-primary-300 lg:px-14">
                    <div class="font-bold tracking-widest text-white uppercase lg:text-xl">04. FRONT BODY</div>
                </div>
                <div class="grid grid-cols-2 px-6 my-10 lg:px-10 xl:px-14">
                    <div v-for="frontBody in props.dataConfig.front_body.data.basic">
                        <input @click.native="form.frontBody = null" class="hidden" type="radio" name="front-body" v-model="form.frontBody" :value="frontBody" :id="'front-body-' + frontBody.slug">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="'front-body-' + frontBody.slug">
                            <img class="h-28" :src="'/'+frontBody.image" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase text-primary-50 2xl:text-lg xl:text-base">{{ frontBody.name }}</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                </div>
                <div class="flex items-center gap-12 px-6 my-10 xl:mx-20 lg:px-10">
                    <div class="text-xs font-bold tracking-widest uppercase text-primary-50 2xl:text-lg xl:text-base">OPTION NUMBER</div>
                    <div class="flex font-roboto">
                        <div v-if="form.front_body?.optionNumber" ref="inputCont" class="box-input-wrapper">
                            <input
                                v-for="(digit, index) in split(form.front_body?.optionNumber)"
                                type="text"
                                maxlength="1"
                                :value="digit"
                                class="box-input"
                            >
                        </div>
                        <div v-else class="box-input-wrapper">
                            <input
                                v-for="(digit, index) in 2"
                                type="text"
                                maxlength="1"
                                class="box-input"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- pocket and hem -->
        <div class="grid grid-cols-2 gap-4">
        <!-- pocket -->
                <div>
                    <div class="flex items-center justify-between px-4 py-2 bg-primary-300 lg:px-14">
                        <div class="font-bold tracking-widest text-white uppercase lg:text-xl">05. POCKET</div>
                    </div>
                    <div class="grid grid-cols-3 px-4 my-10 lg:px-10 xl:px-14">
                    <div v-for="pocket in props.dataConfig.pocket.data.basic">
                        <input @click.native="form.pocket = null" class="hidden" type="radio" v-model="form.pocket" :value="pocket" name="pocket" :id="'pocket-'+pocket.slug">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="'pocket-'+pocket.slug">
                            <img class="h-28" :src="'/'+pocket.image" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase text-primary-50 2xl:text-lg xl:text-base">{{ pocket.name }}</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                </div>
            </div>
        <!-- hem -->
            <div>
                <div class="flex items-center justify-between px-4 py-2 bg-primary-300 lg:px-14">
                    <div class="font-bold tracking-widest text-white uppercase lg:text-xl">06. HEM</div>
                </div>
                <div class="grid grid-cols-2 px-4 my-10 lg:px-10 xl:px-14">
                    <div v-for="hem in props.dataConfig.hem.data.basic">
                        <input @click.native="form.hem = null" class="hidden" type="radio" name="hem" v-model="form.hem"  :value="hem" :id="'hem-'+hem.slug">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="'hem-'+hem.slug">
                            <img class="h-28" :src="'/'+hem.image" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase text-primary-50 2xl:text-lg xl:text-base">{{ hem.name }}</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- back body -->
        <div>
            <div class="flex items-center justify-between px-4 py-2 bg-primary-300 lg:px-14">
                <div class="font-bold tracking-widest text-white uppercase lg:text-xl">07. BACK BODY</div>
            </div>
            <div class="grid grid-cols-4 p-6 my-8 xl:my-10 lg:px-10 xl:px-14">
                <div v-for="backBody in props.dataConfig.back_body.data.basic">
                    <input @click.native="form.backBody = null" class="hidden" type="radio" name="back-body" v-model="form.backBody" :value="backBody" :id="'back-body-'+backBody.slug">
                    <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="'back-body-'+backBody.slug">
                        <img class="h-28" :src="'/'+backBody.image" alt="">
                        <div class="text-xs font-bold tracking-widest text-center uppercase text-primary-50 2xl:text-lg xl:text-base">{{ backBody.name }}</div>
                        <span class="checkbox-inner"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- button -->
        <div>
            <div class="flex items-center justify-between px-4 py-2 bg-primary-300 lg:px-14">
                <div class="font-bold tracking-widest text-white uppercase lg:text-xl">08. BUTTON</div>
            </div>
            <div class="grid grid-cols-5 gap-4 px-6 my-10 xl:grid-cols-9 lg:px-10 xl:px-14">
                <div v-for="button in props.dataConfig.button.data.basic">
                    <input @click.native="form.button = null" class="hidden" type="radio" name="button" :id="`button-${button.slug}`" v-model="form.button" :value="button">
                    <label class="flex flex-col items-center justify-between h-full px-2 rounded cursor-pointer" :for="`button-${button.slug}`">
                        <img class="h-28" :src="'/'+button.image" alt="">
                        <div class="text-xs font-bold tracking-widest text-center uppercase text-primary-50 2xl:text-lg xl:text-base">{{ button.name }}</div>
                        <span class="mt-4 checkbox-inner"></span>
                    </label>
                </div>
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between px-4 py-2 bg-primary-300 lg:px-14">
                <div class="font-bold tracking-widest text-white uppercase lg:text-xl">SIZE</div>
            </div>
            <div class="grid grid-cols-3 gap-2 px-6 my-10 xl:grid-cols-4 lg:px-10 xl:px-14">
                <div>
                    <input v-model="formSize.order" class="hidden" value="1. NEW ORDER" type="radio" name="size" :id="`new-order`">
                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`new-order`">
                        <div class="text-xs font-bold tracking-widest text-center uppercase text-primary-50 2xl:text-lg xl:text-base">1. NEW ORDER</div>
                        <span class="checkbox-inner"></span>
                    </label>
                </div>
                <div class="justify-self-center xl:col-span-2">
                    <input v-model="formSize.order" class="hidden" type="radio" value="2. REPEAT ORDER" name="size" :id="`repeat-order`">
                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`repeat-order`">
                        <div class="text-xs font-bold tracking-widest text-center uppercase text-primary-50 2xl:text-lg xl:text-base">2. REPEAT ORDER</div>
                        <span class="checkbox-inner"></span>
                    </label>
                </div>
                <div>
                    <input v-model="formSize.order" class="hidden" type="radio" name="size" value="3. GARMENT SAMPLE" :id="`garment-sample`">
                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`garment-sample`">
                        <div class="text-xs font-bold tracking-widest text-center uppercase text-primary-50 2xl:text-lg xl:text-base">3. GARMENT SAMPLE</div>
                        <span class="checkbox-inner"></span>
                    </label>
                </div>
            </div>
            <!-- body type -->
            <div class="grid grid-cols-4 grid-rows-2 gap-2 px-6 my-10 lg:px-10 xl:px-14">
                <div class="max-xl:col-span-4 xl:row-span-2">
                    <div class="inline-block border-2 border-primary-50 px-2 pt-1.5 font-bold text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">BODY TYPE</div>
                </div>
                <div class="max-xl:col-span-2">
                    <input v-model="formSize.bodyType" value="2. SLIM" class="hidden" type="radio" name="body-type" :id="`slim`">
                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`slim`">
                        <div class="text-xs font-bold tracking-widest text-center uppercase text-primary-50 2xl:text-lg xl:text-base">2. SLIM</div>
                        <span class="checkbox-inner"></span>
                    </label>
                </div>
                <div class="max-xl:col-span-2">
                    <input v-model="formSize.bodyType" value="3. STANDARD I" class="hidden" type="radio" name="body-type" :id="`standard-1`">
                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`standard-1`">
                        <div class="text-xs font-bold tracking-widest text-center uppercase text-primary-50 2xl:text-lg xl:text-base">3. STANDARD I</div>
                        <span class="checkbox-inner"></span>
                    </label>
                </div>
                <div class="max-xl:col-span-2">
                    <input v-model="formSize.bodyType" value="4. STANDARD II" class="hidden" type="radio" name="body-type" :id="`standard-2`">
                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`standard-2`">
                        <div class="text-xs font-bold tracking-widest text-center uppercase text-primary-50 2xl:text-lg xl:text-base">4. STANDARD II</div>
                        <span class="checkbox-inner"></span>
                    </label>
                </div>
                <div class="max-xl:col-span-2">
                    <input v-model="formSize.bodyType" value="5. BIG I" class="hidden" type="radio" name="body-type" :id="`big-1`">
                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`big-1`">
                        <div class="text-xs font-bold tracking-widest text-center uppercase text-primary-50 2xl:text-lg xl:text-base">5. BIG I</div>
                        <span class="checkbox-inner"></span>
                    </label>
                </div>
                <div class="max-xl:col-span-2">
                    <input v-model="formSize.bodyType" value="7. BIG II" class="hidden" type="radio" name="body-type" :id="`big-2`">
                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`big-2`">
                        <div class="text-xs font-bold tracking-widest text-center uppercase text-primary-50 2xl:text-lg xl:text-base">7. BIG II</div>
                        <span class="checkbox-inner"></span>
                    </label>
                </div>
                <div class="max-xl:col-span-2">
                    <input v-model="formSize.bodyType" value="3. STANDARD II" class="hidden" type="radio" name="body-type" :id="`standard-3`">
                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`standard-3`">
                        <div class="text-xs font-bold tracking-widest text-center uppercase text-primary-50 2xl:text-lg xl:text-base">3. STANDARD II</div>
                        <span class="checkbox-inner"></span>
                    </label>
                </div>
            </div>

            <!-- sleeve -->
            <div class="grid grid-cols-4 grid-rows-2 gap-2 px-6 mt-6 lg:px-10 xl:px-14">
                <div class="max-xl:col-span-4 xl:row-span-2">
                    <div class="inline-block border-2 border-primary-50 px-2 pt-1.5 font-bold text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">SLEEVE</div>
                </div>
                <div class="max-xl:col-span-2">
                    <input v-model="formSize.sleeve" value="1. SLIM SLEEVE" class="hidden" type="radio" name="sleeve" :id="`slim-sleeve`">
                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`slim-sleeve`">
                        <div class="text-xs font-bold tracking-widest text-center uppercase text-primary-50 2xl:text-lg xl:text-base">1. SLIM SLEEVE</div>
                        <span class="checkbox-inner"></span>
                    </label>
                </div>
                <div class="max-xl:col-span-2">
                    <input v-model="formSize.sleeve" value="2. REGULAR SLEEVE" class="hidden" type="radio" name="sleeve" :id="`regular-sleeve`">
                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`regular-sleeve`">
                        <div class="text-xs font-bold tracking-widest text-center uppercase text-primary-50 2xl:text-lg xl:text-base">2. REGULAR SLEEVE</div>
                        <span class="checkbox-inner"></span>
                    </label>
                </div>
            </div>

            <div class="px-6 mt-4 lg:px-10 xl:px-14">
                <div class="overflow-x-auto">
                    <table class="w-full text-primary-50">
                        <thead>
                            <tr class="*:border-2 *:border-primary-50 *:px-2 *:pt-2 *:pb-1">
                                <th>MEASURE</th>
                                <th>NECK</th>
                                <th>R.SLEEVE</th>
                                <th>L.SLEEVE</th>
                                <th>CHEST</th>
                                <th>WAIST</th>
                                <th>SHOULDER</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="*:border-2 *:border-primary-50 *:text-center">
                                <td>SHIRT</td>
                                <td>
                                    <input v-model="formSize.shirt.neck" type="text" class="w-full text-center font-roboto">
                                </td>
                                <td>
                                    <input v-model="formSize.shirt.rightSleeve" type="text" class="w-full text-center font-roboto">
                                </td>
                                <td>
                                    <input v-model="formSize.shirt.leftSleeve" type="text" class="w-full text-center font-roboto">
                                </td>
                                <td>
                                    <input v-model="formSize.shirt.chest" type="text" class="w-full text-center font-roboto">
                                </td>
                                <td>
                                    <input v-model="formSize.shirt.waist" type="text" class="w-full text-center font-roboto">
                                </td>
                                <td>
                                    <input v-model="formSize.shirt.shoulder" type="text" class="w-full text-center font-roboto">
                                </td>
                            </tr>
                            <tr class="*:border-2 *:border-primary-50 *:text-center">
                                <td>ACTUAL</td>
                                <td>
                                    <input v-model="formSize.actual.neck" type="text" class="w-full text-center font-roboto">
                                </td>
                                <td>
                                    <input v-model="formSize.actual.rightSleeve" type="text" class="w-full text-center font-roboto">
                                </td>
                                <td>
                                    <input v-model="formSize.actual.leftSleeve" type="text" class="w-full text-center font-roboto">
                                </td>
                                <td>
                                    <input v-model="formSize.actual.chest" type="text" class="w-full text-center font-roboto">
                                </td>
                                <td>
                                    <input v-model="formSize.actual.waist" type="text" class="w-full text-center font-roboto">
                                </td>
                                <td>
                                    <input v-model="formSize.actual.shoulder" type="text" class="w-full text-center font-roboto">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="grid grid-cols-2 xl:grid-cols-4 mt-10 mb-10 *:px-2 *:pt-2 *:pb-1 text-primary-50 tracking-widest whitespace-pre">
                    <div class="border-2 border-primary-50">SPECIAL ADJUSTMENT</div>
                    <div class="flex border-r-2 border-primary-50 border-y-2">
                        <div>NECK SIZE :</div>
                        <div>
                            <input v-model="formSize.sa.neckSize" type="text" class="w-full text-center font-roboto">
                        </div>
                    </div>
                    <div class="flex border-r-2 border-primary-50 border-y-2 max-xl:border-l-2">
                        <div>SHOULDER :</div>
                        <div>
                            <input v-model="formSize.sa.shoulder" type="text" class="w-full text-center font-roboto">
                        </div>
                    </div>
                    <div class="flex border-r-2 border-primary-50 border-y-2">
                        <div>BACK LENGTH :</div>
                        <div>
                            <input v-model="formSize.sa.backLength" type="text" class="w-full text-center font-roboto">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-32">
            <div class="flex items-center justify-between px-4 py-2 bg-primary-300 lg:px-14">
                <div class="font-bold tracking-widest text-white uppercase lg:text-xl">ADDITIONAL NOTES</div>
            </div>
            <div class="grid grid-cols-5 gap-3 px-6 my-10 lg:px-10 xl:px-14">
                <div class="col-span-3">
                    <textarea v-model="additionalNote" class="w-full h-full p-2 border-2 border-primary-50 font-roboto placeholder:font-josefin placeholder:tracking-widest placeholder-primary-50" name="" id="" placeholder="NOTE"></textarea>
                </div>
                <div class="col-span-2 space-y-2">
                    <input v-model="discount" type="number"  class="w-full px-4 pt-2 pb-1 border-2 number-input border-primary-50 text-primary-50" placeholder="DISCOUNT"/>
                    <input v-model="price" type="number" class="w-full px-4 pt-2 pb-1 border-2 number-input border-primary-50 text-primary-50" placeholder="RP" />
                    <div>
                        <button @click="basicAmount()" class="w-full px-5 pt-3 pb-2 text-center bg-secondary text-primary-50">APPLY PRICE</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<style scoped>
    input[type="radio"] + label span.checkbox-inner {
        @apply border-primary-50;
    }
    input[type="radio"]:checked + label span.checkbox-inner {
        @apply border-primary-50 bg-primary-50;
        color: #fff;
        background-image: url("data:image/svg+xml,%3C%3Fxml version='1.0' encoding='UTF-8'%3F%3E%3Csvg width='14px' height='10px' viewBox='0 0 14 10' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'%3E%3C!-- Generator: Sketch 59.1 (86144) - https://sketch.com --%3E%3Ctitle%3Echeck%3C/title%3E%3Cdesc%3ECreated with Sketch.%3C/desc%3E%3Cg id='Page-1' stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'%3E%3Cg id='ios_modification' transform='translate(-27.000000, -191.000000)' fill='%23FFFFFF' fill-rule='nonzero'%3E%3Cg id='Group-Copy' transform='translate(0.000000, 164.000000)'%3E%3Cg id='ic-check-18px' transform='translate(25.000000, 23.000000)'%3E%3Cpolygon id='check' points='6.61 11.89 3.5 8.78 2.44 9.84 6.61 14 15.56 5.05 14.5 4'%3E%3C/polygon%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        background-size: 14px 10px;
    }
    .checkbox-inner {
        @apply flex justify-center items-center border border-primary-50 text-transparent size-7;
        background: transparent no-repeat center;
    }
</style>
