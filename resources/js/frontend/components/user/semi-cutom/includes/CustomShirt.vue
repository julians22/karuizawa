<script setup>
    import { ref, watch, defineEmits } from 'vue';

    const props = defineProps({
        dataCustomShirt: JSON
    });

    console.log(props.dataCustomShirt);


    const emitFrom = defineEmits(['form-custom-shirt']);

    const form = ref({
        fabric: {
            index: 'Fabric',
            name: null,
            text: ''
        },
        collar: {
            index: 'Collar',
            name: null,
            optionNumber: null
        },
        cuff: {
            index: 'Cuff',
            name: null,
            optionNumber: null
        },
        frontBody: {
            index: 'Front Body',
            name: null,
            optionNumber: null
        },
        pocket: {
            index: 'Pocket',
            name: null
        },
        hem: {
            index: 'Hem',
            name: null
        },
        backBody: {
            index: 'Back Body',
            name: null
        },
        button: {
            index: 'Button',
            name: null
        }
    });

    const data = ref({
        fabricCode1: '',
        fabricCode2: '',
        fabricCode3: '',
        fabricCode4: '',
        fabricCodeText: '',
        collarOptionNumber1: '',
        collarOptionNumber2: '',

    });
    watch(data.value, () => {
        form.value.fabric.name = `${data.value.fabricCode1}` + `${data.value.fabricCode2}` + `${data.value.fabricCode3}` + `${data.value.fabricCode4}`;
        form.value.fabric.text = data.value.fabricCodeText;
        form.value.collar.optionNumber = `${data.value.collarOptionNumber1}` + `${data.value.collarOptionNumber2}`;
    })

    watch(form.value, () => {
        emitFrom('form-custom-shirt', form.value);
    })
</script>

<template>
    <div>
        <div class="relative min-h-svh">
            <div class="flex items-center justify-between p-6 lg:py-7 lg:px-14 bg-primary-50">
                <div class="text-lg font-bold tracking-widest text-white uppercase lg:text-xl">CUSTOM MADE SHIRT</div>
            </div>

            <!-- fabric code -->
            <div>
                <div class="flex items-center justify-between px-4 py-2 lg:px-14 bg-primary-300">
                    <div class="font-bold tracking-widest text-white uppercase lg:text-xl">01. FABRIC</div>
                </div>
                <div class="flex items-center gap-8 px-6 my-5 lg:px-10 xl:px-14 fabric-code">
                    <label for="fabric-code" class="tracking-widest uppercase text-primary-50 lg:whitespace-pre-wrap">fabric code</label>
                    <div class="flex font-roboto">
                        <input v-model="data.fabricCode1" type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-2 size-10 border-primary-50">
                        <input v-model="data.fabricCode2" type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r-2 size-10 border-y-2 border-primary-50">
                        <input v-model="data.fabricCode3" type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r-2 size-10 border-y-2 border-primary-50">
                        <input v-model="data.fabricCode4" type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r-2 size-10 border-y-2 border-primary-50">
                    </div>
                    <input v-model="data.fabricCodeText" type="text" class="block w-full h-10 p-2 text-sm text-gray-900 border-2 border-r-2 border-primary-50 font-roboto">
                </div>
            </div>

            <!-- collar -->
            <div>
                <div class="flex items-center justify-between px-4 py-2 lg:px-14 bg-primary-300">
                    <div class="font-bold tracking-widest text-white uppercase lg:text-xl">02. COLLAR</div>
                </div>
                <div class="grid grid-cols-6 px-6 my-10 lg:px-10 xl:px-14">
                    <div v-for="collar in props.dataCustomShirt.collar">
                        <input class="hidden" type="radio" v-model="form.collar.name" :value="collar.name"  :id="'collar-' + collar.slug">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="'collar-' + collar.slug">
                            <img class="h-24" :src="collar.img" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">{{ collar.name }}</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                </div>
                <div class="flex items-center gap-12 mx-20 my-10">
                    <div class="text-xs font-bold tracking-widest uppercase xl:text-base 2xl:text-lg text-primary-50">OPTION NUMBER</div>
                    <div class="flex font-roboto">
                        <input type="number" v-model="data.collarOptionNumber1" class="block p-2 text-sm text-center text-gray-900 border-2 size-10 border-primary-50">
                        <input type="number" v-model="data.collarOptionNumber2" class="block p-2 text-sm text-center text-gray-900 border-r-2 size-10 border-y-2 border-primary-50">
                    </div>
                </div>
            </div>

            <!-- cuffs and  front body -->
            <div class="grid grid-cols-2 gap-4">
                <!-- cuffs -->
                <div>
                    <div class="flex items-center justify-between px-4 py-2 lg:px-14 bg-primary-300">
                        <div class="font-bold tracking-widest text-white uppercase lg:text-xl">03. CUFFS</div>
                    </div>
                    <div class="grid grid-cols-3 px-6 my-10 lg:px-10 xl:px-14">
                        <div v-for="cuff in props.dataCustomShirt.cuffs">
                            <input class="hidden" type="radio" v-model="form.cuff.name" name="cuff" :value="cuff.name" :id="'cuff-' + cuff.slug">
                            <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="'cuff-' + cuff.slug">
                                <img class="h-28" :src="cuff.img" alt="">
                                <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">{{ cuff.name }}</div>
                                <span class="checkbox-inner"></span>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center gap-12 px-6 my-10 xl:mx-20">
                        <div class="text-xs font-bold tracking-widest uppercase xl:text-base 2xl:text-lg text-primary-50">OPTION NUMBER</div>
                        <div class="flex font-roboto">
                            <input type="number" class="block p-2 text-sm text-center text-gray-900 border-2 size-10 border-primary-50">
                            <input type="number" class="block p-2 text-sm text-center text-gray-900 border-r-2 size-10 border-y-2 border-primary-50">
                        </div>
                    </div>
                </div>
                <!-- front body -->
                <div>
                    <div class="flex items-center justify-between px-4 py-2 lg:px-14 bg-primary-300">
                        <div class="font-bold tracking-widest text-white uppercase lg:text-xl">04. FRONT BODY</div>
                    </div>
                    <div class="grid grid-cols-2 px-6 my-10 lg:px-10 xl:px-14">
                        <div v-for="frontBody in props.dataCustomShirt.frontBody">
                            <input class="hidden" type="radio" name="front-body" v-model="form.frontBody.name" :value="frontBody.name" :id="'front-body-' + frontBody.slug">
                            <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="'front-body-' + frontBody.slug">
                                <img class="h-28" :src="frontBody.img" alt="">
                                <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">{{ frontBody.name }}</div>
                                <span class="checkbox-inner"></span>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center gap-12 px-6 my-10 lg:px-10 xl:mx-20">
                        <div class="text-xs font-bold tracking-widest uppercase xl:text-base 2xl:text-lg text-primary-50">OPTION NUMBER</div>
                        <div class="flex font-roboto">
                            <input type="number" class="block p-2 text-sm text-center text-gray-900 border-2 size-10 border-primary-50">
                            <input type="number" class="block p-2 text-sm text-center text-gray-900 border-r-2 size-10 border-y-2 border-primary-50">
                        </div>
                    </div>
                </div>
            </div>

            <!-- pocket and hem -->
            <div class="grid grid-cols-2 gap-4">
                <!-- pocket -->
                        <div>
                            <div class="flex items-center justify-between px-4 py-2 lg:px-14 bg-primary-300">
                                <div class="font-bold tracking-widest text-white uppercase lg:text-xl">05. POCKET</div>
                            </div>
                            <div class="grid grid-cols-3 px-4 my-10 lg:px-10 xl:px-14">
                            <div v-for="pocket in props.dataCustomShirt.pocket">
                                <input class="hidden" type="radio" v-model="form.pocket.name" :value="pocket.name" name="pocket" :id="'pocket-'+pocket.slug">
                                <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="'pocket-'+pocket.slug">
                                    <img class="h-28" :src="pocket.img" alt="">
                                    <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">{{ pocket.name }}</div>
                                    <span class="checkbox-inner"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                <!-- hem -->
                    <div>
                        <div class="flex items-center justify-between px-4 py-2 lg:px-14 bg-primary-300">
                            <div class="font-bold tracking-widest text-white uppercase lg:text-xl">06. HEM</div>
                        </div>
                        <div class="grid grid-cols-2 px-4 my-10 lg:px-10 xl:px-14">
                        <div v-for="hem in props.dataCustomShirt.hem">
                            <input class="hidden" type="radio" name="hem" v-model="form.hem.name"  :value="hem.name" :id="'hem-'+hem.slug">
                            <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="'hem-'+hem.slug">
                                <img class="h-28" :src="hem.img" alt="">
                                <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">{{ hem.name }}</div>
                                <span class="checkbox-inner"></span>
                            </label>
                        </div>
                    </div>
                    </div>
            </div>

            <!-- back body -->
            <div>
                <div class="flex items-center justify-between px-4 py-2 lg:px-14 bg-primary-300">
                    <div class="font-bold tracking-widest text-white uppercase lg:text-xl">07. BACK BODY</div>
                </div>
                <div class="grid grid-cols-4 p-6 my-8 xl:my-10 lg:px-10 xl:px-14">
                    <div v-for="backBody in props.dataCustomShirt.backBody">
                        <input class="hidden" type="radio" name="back-body" v-model="form.backBody.name" :value="backBody.name" :id="'back-body-'+backBody.slug">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="'back-body-'+backBody.slug">
                            <img class="h-28" :src="backBody.img" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">{{ backBody.name }}</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- button -->
            <div>
                <div class="flex items-center justify-between px-4 py-2 lg:px-14 bg-primary-300">
                    <div class="font-bold tracking-widest text-white uppercase lg:text-xl">08. BUTTON</div>
                </div>
                <div class="grid grid-cols-5 gap-4 px-6 my-10 xl:grid-cols-9 lg:px-10 xl:px-14">
                    <div v-for="button in props.dataCustomShirt.button">
                        <input class="hidden" type="radio" name="button" :id="`button-${button.slug}`" v-model="form.button.name" :value="button.name">
                        <label class="flex flex-col items-center justify-between h-full px-2 rounded cursor-pointer" :for="`button-${button.slug}`">
                            <img class="h-28" :src="button.img" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">{{ button.name }}</div>
                            <span class="mt-4 checkbox-inner"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- size -->
            <div>
                <div class="flex items-center justify-between px-4 py-2 lg:px-14 bg-primary-300">
                    <div class="font-bold tracking-widest text-white uppercase lg:text-xl">SIZE</div>
                </div>
                <div class="grid grid-cols-3 gap-2 px-6 my-10 xl:grid-cols-4 lg:px-10 xl:px-14">
                    <div>
                        <input class="hidden" type="radio" name="size" :id="`new-order`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`new-order`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">1. NEW ORDER</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="xl:col-span-2 justify-self-center">
                        <input class="hidden" type="radio" name="size" :id="`repeat-order`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`repeat-order`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">2. REPEAT ORDER</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="size" :id="`garment-sample`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`garment-sample`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">3. GARMENT SAMPLE</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                </div>
                <!-- body type -->
                <div class="grid grid-cols-4 grid-rows-2 gap-2 px-6 my-10 lg:px-10 xl:px-14">
                    <div class="max-xl:col-span-4 xl:row-span-2">
                        <div class="inline-block pt-1.5 px-2 text-xs font-bold tracking-widest uppercase border-2 xl:text-base 2xl:text-lg text-primary-50 border-primary-50">BODY TYPE</div>
                    </div>
                    <div class="max-xl:col-span-2">
                        <input class="hidden" type="radio" name="body-type" :id="`slim`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`slim`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">2. SLIM</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="max-xl:col-span-2">
                        <input class="hidden" type="radio" name="body-type" :id="`standard-1`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`standard-1`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">3. STANDARD I</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="max-xl:col-span-2">
                        <input class="hidden" type="radio" name="body-type" :id="`standard-2`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`standard-2`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">4. STANDARD II</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="max-xl:col-span-2">
                        <input class="hidden" type="radio" name="body-type" :id="`big-1`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`big-1`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">5. BIG I</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="max-xl:col-span-2">
                        <input class="hidden" type="radio" name="body-type" :id="`big-2`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`big-2`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">7. BIG II</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="max-xl:col-span-2">
                        <input class="hidden" type="radio" name="body-type" :id="`standard-3`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`standard-3`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">3. STANDARD II</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                </div>

                <!-- sleeve -->
                <div class="grid grid-cols-4 grid-rows-2 gap-2 px-6 mt-6 lg:px-10 xl:px-14">
                    <div class="max-xl:col-span-4 xl:row-span-2">
                        <div class="inline-block pt-1.5 px-2 text-xs font-bold tracking-widest uppercase border-2 xl:text-base 2xl:text-lg text-primary-50 border-primary-50">SLEEVE</div>
                    </div>
                    <div class="max-xl:col-span-2">
                        <input class="hidden" type="radio" name="sleeve" :id="`slim-sleeve`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`slim-sleeve`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">1. SLIM SLEEVE</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="max-xl:col-span-2">
                        <input class="hidden" type="radio" name="sleeve" :id="`regular-sleeve`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`regular-sleeve`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">2. REGULAR SLEEVE</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                </div>

                <div class="px-6 mt-4 lg:px-10 xl:px-14">
                    <div class="overflow-x-auto">
                        <table class="w-full text-primary-50">
                            <thead>
                                <tr class="*:px-2 *:pt-2 *:pb-1 *:border-2 *:border-primary-50">
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
                                        <input type="text" class="w-full text-center font-roboto">
                                    </td>
                                    <td>
                                        <input type="text" class="w-full text-center font-roboto">
                                    </td>
                                    <td>
                                        <input type="text" class="w-full text-center font-roboto">
                                    </td>
                                    <td>
                                        <input type="text" class="w-full text-center font-roboto">
                                    </td>
                                    <td>
                                        <input type="text" class="w-full text-center font-roboto">
                                    </td>
                                    <td>
                                        <input type="text" class="w-full text-center font-roboto">
                                    </td>
                                </tr>
                                <tr class="*:border-2 *:border-primary-50 *:text-center">
                                    <td>ACTUAL</td>
                                    <td>
                                        <input type="text" class="w-full text-center font-roboto">
                                    </td>
                                    <td>
                                        <input type="text" class="w-full text-center font-roboto">
                                    </td>
                                    <td>
                                        <input type="text" class="w-full text-center font-roboto">
                                    </td>
                                    <td>
                                        <input type="text" class="w-full text-center font-roboto">
                                    </td>
                                    <td>
                                        <input type="text" class="w-full text-center font-roboto">
                                    </td>
                                    <td>
                                        <input type="text" class="w-full text-center font-roboto">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="grid grid-cols-2 xl:grid-cols-4 mt-10 mb-10 tracking-widest text-primary-50 *:pt-2 *:pb-1 *:px-2 whitespace-pre">
                        <div class="border-2 border-primary-50">SPECIAL ADJUSTMENT</div>
                        <div class="flex border-r-2 border-y-2 border-primary-50">
                            <div>NECK SIZE :</div>
                            <div>
                                <input type="text" class="w-full text-center font-roboto">
                            </div>
                        </div>
                        <div class="flex border-r-2 max-xl:border-l-2 border-y-2 border-primary-50">
                            <div>SHOULDER :</div>
                            <div>
                                <input type="text" class="w-full text-center font-roboto">
                            </div>
                        </div>
                        <div class="flex border-r-2 border-y-2 border-primary-50">
                            <div>BACK LENGTH :</div>
                            <div>
                                <input type="text" class="w-full text-center font-roboto">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-32">
                <div class="flex items-center justify-between px-4 py-2 lg:px-14 bg-primary-300">
                    <div class="font-bold tracking-widest text-white uppercase lg:text-xl">ADDITIONAL NOTES</div>
                </div>
                <div class="grid grid-cols-5 gap-3 px-6 my-10 lg:px-10 xl:px-14">
                    <div class="col-span-3">
                        <textarea class="w-full h-full p-2 border-2 placeholder:font-josefin font-roboto border-primary-50 placeholder-primary-50 placeholder:tracking-widest" name="" id="" placeholder="NOTE"></textarea>
                    </div>
                    <div class="col-span-2 space-y-2">
                        <input type="text"  class="w-full px-4 pt-2 pb-1 border-2 border-primary-50 text-primary-50" placeholder="DISCOUNT"/>
                        <input type="text" class="w-full px-4 pt-2 pb-1 border-2 border-primary-50 text-primary-50" placeholder="RP" />
                        <div>
                            <button class="w-full px-5 pt-3 pb-2 text-center text-primary-50 bg-secondary">APPLY PRICE</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 right-0 flex">
            <button class="flex items-center gap-2 p-6 tracking-widest text-white bg-primary-300">
                <span>ADD CUSTOM REQUEST </span>
                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
            </button>
            <button class="flex items-center gap-2 p-6 tracking-widest text-white bg-secondary-50">
                <span>SUBMIT</span>
                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
            </button>
        </div>
    </div>
</template>

<style scoped>
    input[type="number"] {
        appearance: textfield;
    }

    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        appearance: none;
    }

    input[type="radio"] + label span.checkbox-inner {
        @apply border-primary-50;
    }
    input[type="radio"]:checked + label span.checkbox-inner {
        @apply bg-primary-50 border-primary-50;
        color: #fff;
        background-image: url("data:image/svg+xml,%3C%3Fxml version='1.0' encoding='UTF-8'%3F%3E%3Csvg width='14px' height='10px' viewBox='0 0 14 10' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'%3E%3C!-- Generator: Sketch 59.1 (86144) - https://sketch.com --%3E%3Ctitle%3Echeck%3C/title%3E%3Cdesc%3ECreated with Sketch.%3C/desc%3E%3Cg id='Page-1' stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'%3E%3Cg id='ios_modification' transform='translate(-27.000000, -191.000000)' fill='%23FFFFFF' fill-rule='nonzero'%3E%3Cg id='Group-Copy' transform='translate(0.000000, 164.000000)'%3E%3Cg id='ic-check-18px' transform='translate(25.000000, 23.000000)'%3E%3Cpolygon id='check' points='6.61 11.89 3.5 8.78 2.44 9.84 6.61 14 15.56 5.05 14.5 4'%3E%3C/polygon%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        background-size: 14px 10px;
    }
    .checkbox-inner {
        @apply flex items-center justify-center text-transparent border-2 size-7 border-primary-50;
        background: transparent no-repeat center;
    }
</style>
