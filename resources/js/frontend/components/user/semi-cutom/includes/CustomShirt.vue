<script setup>
    import { ref, watch, defineEmits, computed } from 'vue';
    import boxInput from '../../../utils/fields/boxInput.vue';
    import { useProducts } from '../../../../store/product';


    const props = defineProps({
        dataSemiCustom: Object
    });

    const dataCustomShirt = computed(() => {
        return props.dataSemiCustom
    });

    const emitFrom = defineEmits({
        'form-custom-shirt': 'form-custom-shirt',
        'additional-basic': 'additional-basic',
    });

    const form = ref({
        fabric: {
            text: null,
            fabricCode: null
        },
        collar: null,
        cuff: null,
        frontBody: null,
        pocket: null,
        hem: null,
        backBody: null,
        button: null,
    });

    const formSize = ref({
        order: null,
        bodyType: null,
        sleeve: null,
        shirt: {
            neck: null,
            rightSleeve: null,
            leftSleeve: null,
            chest: null,
            waist: null,
            shoulder: null,
        },
        actual: {
            neck: null,
            rightSleeve: null,
            leftSleeve: null,
            chest: null,
            waist: null,
            shoulder: null,
        },
        sa: {
            neckSize: null,
            backLength: null,
            shoulder: null,
        }
    })

    watch(form.value, (items) => {
        let radio = document.querySelectorAll("input[type=radio]:checked");

        // unchecked radio
        for(let i = 0; i < radio.length; i++) {
            radio[i].onclick = function(e) {
                let myVariable = e.target.name;

                let camelCased = myVariable.replace(/-([a-z])/g, function (g) { return g[1].toUpperCase(); });

                // find form value key
                const key = Object.keys(form.value).find(key => key === camelCased);
                form.value[key] = null;
            }
        }

    });

    const additionalNote = ref(null);

    const price = ref(null);
    const discount = ref(null);

    const amount = ref({
            price: 0,
            discount: 0,
            total: 0
    });

    const basicAmount = () => {
        let x = parseInt(price?.value) ? parseInt(price?.value) : 0;
        let disc = parseInt(discount?.value) ? parseInt(discount?.value) : 0;
        let z = x - (x * disc / 100);
        amount.value.price = x;
        amount.value.discount = disc;
        amount.value.total = z;

        emitFrom('additional-basic', amount);
    }

    defineExpose({
        form,
        formSize,
        additionalNote,
        amount,
        basicAmount
    });


    watch(form.value, () => {
        emitFrom('form-custom-shirt', form.value);
    });

    function onInputBox(val, key = 'fabric', key2 = 'fabricCode')
    {
        if (key2 == 'fabricCode') {
            form.value[key][key2] = val;
        }
        if (key2 == 'optionNumber') {
            form.value[key] = {};
            form.value[key][key2] = val;

        }
    }

    const currencyFormat = (value) => {
        if (!value || value == null || value == undefined || value == ''){
            return ''
        }
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(
            value,
        )
    };
</script>

<template>
    <div>
        <div class="relative min-h-svh">
            <div class="flex justify-between items-center bg-primary-50 lg:px-14 lg:py-7 p-6">
                <div class="font-bold text-lg text-white lg:text-xl uppercase tracking-widest">CUSTOM MADE SHIRT</div>
            </div>

            <!-- fabric code -->
            <div>
                <div class="flex justify-between items-center bg-primary-300 px-4 lg:px-14 py-2">
                    <div class="font-bold text-white lg:text-xl uppercase tracking-widest">01. FABRIC</div>
                </div>
                <div class="flex items-center gap-8 my-5 px-6 lg:px-10 xl:px-14 fabric-code">
                    <label for="fabric-code" class="text-primary-50 uppercase tracking-widest lg:whitespace-pre-wrap">fabric code</label>
                    <boxInput :digitCount="4" @update:input="onInputBox($event, 'fabric', 'fabricCode')"/>
                    <input v-model="form.fabric.text" type="text" class="block border-primary-50 p-2 border border-r w-full h-8 font-roboto text-gray-900 text-sm">
                </div>
            </div>

            <!-- collar -->
            <div>
                <div class="flex justify-between items-center bg-primary-300 px-4 lg:px-14 py-2">
                    <div class="font-bold text-white lg:text-xl uppercase tracking-widest">02. COLLAR</div>
                </div>
                <div class="grid grid-cols-6 my-10 px-6 lg:px-10 xl:px-14">
                    <div v-for="collar in dataCustomShirt.collar.data.basic">
                        <input class="hidden" type="radio" v-model="form.collar" :value="collar" name="collar"  :id="'collar-' + collar.slug">
                        <label class="flex flex-col justify-between items-center gap-4 px-2 rounded h-full cursor-pointer" :for="'collar-' + collar.slug">
                            <img class="h-24" :src="collar.image" alt="">
                            <div class="font-bold text-center text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">{{ collar.name }}</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                </div>
                <div class="flex items-center gap-12 mx-20 my-10">
                    <div class="font-bold text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">OPTION NUMBER</div>
                    <div class="flex font-roboto">
                        <boxInput :digitCount="2" inputType="number" @update:input="onInputBox($event, 'collar', 'optionNumber')"/>
                    </div>
                </div>
            </div>

            <!-- cuffs and  front body -->
            <div class="gap-4 grid grid-cols-2">
                <!-- cuffs -->
                <div>
                    <div class="flex justify-between items-center bg-primary-300 px-4 lg:px-14 py-2">
                        <div class="font-bold text-white lg:text-xl uppercase tracking-widest">03. CUFFS</div>
                    </div>
                    <div class="grid grid-cols-3 my-10 px-6 lg:px-10 xl:px-14">
                        <div v-for="cuff in dataCustomShirt.cuffs.data.basic">
                            <input class="hidden" type="radio" v-model="form.cuff" name="cuff" :value="cuff" :id="'cuff-' + cuff.slug">
                            <label class="flex flex-col justify-between items-center gap-4 px-2 rounded h-full cursor-pointer" :for="'cuff-' + cuff.slug">
                                <img class="h-24 xl:h-28" :src="cuff.image" alt="">
                                <div class="font-bold text-center text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">{{ cuff.name }}</div>
                                <span class="checkbox-inner"></span>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center gap-12 xl:mx-20 my-10 px-6">
                        <div class="font-bold text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">OPTION NUMBER</div>
                        <div class="flex font-roboto">
                            <boxInput :digitCount="2" inputType="number" @update:input="onInputBox($event, 'cuff', 'optionNumber')"/>
                        </div>
                    </div>
                </div>
                <!-- front body -->
                <div>
                    <div class="flex justify-between items-center bg-primary-300 px-4 lg:px-14 py-2">
                        <div class="font-bold text-white lg:text-xl uppercase tracking-widest">04. FRONT BODY</div>
                    </div>
                    <div class="grid grid-cols-2 my-10 px-6 lg:px-10 xl:px-14">
                        <div v-for="frontBody in dataCustomShirt.front_body.data.basic">
                            <input class="hidden" type="radio" name="front-body" v-model="form.frontBody" :value="frontBody" :id="'front-body-' + frontBody.slug">
                            <label class="flex flex-col justify-between items-center gap-4 px-2 rounded h-full cursor-pointer" :for="'front-body-' + frontBody.slug">
                                <img class="h-28" :src="frontBody.image" alt="">
                                <div class="font-bold text-center text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">{{ frontBody.name }}</div>
                                <span class="checkbox-inner"></span>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center gap-12 xl:mx-20 my-10 px-6 lg:px-10">
                        <div class="font-bold text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">OPTION NUMBER</div>
                        <div class="flex font-roboto">
                            <boxInput :digitCount="2" inputType="number" @update:input="onInputBox($event, 'frontBody', 'optionNumber')"/>
                        </div>
                    </div>
                </div>
            </div>

            <!-- pocket and hem -->
            <div class="gap-4 grid grid-cols-2">
                <!-- pocket -->
                        <div>
                            <div class="flex justify-between items-center bg-primary-300 px-4 lg:px-14 py-2">
                                <div class="font-bold text-white lg:text-xl uppercase tracking-widest">05. POCKET</div>
                            </div>
                            <div class="grid grid-cols-3 my-10 px-4 lg:px-10 xl:px-14">
                            <div v-for="pocket in dataCustomShirt.pocket.data.basic">
                                <input class="hidden" type="radio" v-model="form.pocket" :value="pocket" name="pocket" :id="'pocket-'+pocket.slug">
                                <label class="flex flex-col justify-between items-center gap-4 px-2 rounded h-full cursor-pointer" :for="'pocket-'+pocket.slug">
                                    <img class="h-28" :src="pocket.image" alt="">
                                    <div class="font-bold text-center text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">{{ pocket.name }}</div>
                                    <span class="checkbox-inner"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                <!-- hem -->
                    <div>
                        <div class="flex justify-between items-center bg-primary-300 px-4 lg:px-14 py-2">
                            <div class="font-bold text-white lg:text-xl uppercase tracking-widest">06. HEM</div>
                        </div>
                        <div class="grid grid-cols-2 my-10 px-4 lg:px-10 xl:px-14">
                        <div v-for="hem in dataCustomShirt.hem.data.basic">
                            <input class="hidden" type="radio" name="hem" v-model="form.hem"  :value="hem" :id="'hem-'+hem.slug">
                            <label class="flex flex-col justify-between items-center gap-4 px-2 rounded h-full cursor-pointer" :for="'hem-'+hem.slug">
                                <img class="h-28" :src="hem.image" alt="">
                                <div class="font-bold text-center text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">{{ hem.name }}</div>
                                <span class="checkbox-inner"></span>
                            </label>
                        </div>
                    </div>
                    </div>
            </div>

            <!-- back body -->
            <div>
                <div class="flex justify-between items-center bg-primary-300 px-4 lg:px-14 py-2">
                    <div class="font-bold text-white lg:text-xl uppercase tracking-widest">07. BACK BODY</div>
                </div>
                <div class="grid grid-cols-4 my-8 xl:my-10 lg:px-10 xl:px-14 p-6">
                    <div v-for="backBody in dataCustomShirt.back_body.data.basic">
                        <input class="hidden" type="radio" name="back-body" v-model="form.backBody" :value="backBody" :id="'back-body-'+backBody.slug">
                        <label class="flex flex-col justify-between items-center gap-4 px-2 rounded h-full cursor-pointer" :for="'back-body-'+backBody.slug">
                            <img class="h-28" :src="backBody.image" alt="">
                            <div class="font-bold text-center text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">{{ backBody.name }}</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- button -->
            <div>
                <div class="flex justify-between items-center bg-primary-300 px-4 lg:px-14 py-2">
                    <div class="font-bold text-white lg:text-xl uppercase tracking-widest">08. BUTTON</div>
                </div>
                <div class="gap-4 grid grid-cols-5 xl:grid-cols-9 my-10 px-6 lg:px-10 xl:px-14">
                    <div v-for="button in dataCustomShirt.button.data.basic">
                        <input class="hidden" type="radio" name="button" :id="`button-${button.slug}`" v-model="form.button" :value="button">
                        <label class="flex flex-col justify-between items-center px-2 rounded h-full cursor-pointer" :for="`button-${button.slug}`">
                            <img class="h-28" :src="button.image" alt="">
                            <div class="font-bold text-center text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">{{ button.name }}</div>
                            <span class="mt-4 checkbox-inner"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- size -->
            <div>
                <div class="flex justify-between items-center bg-primary-300 px-4 lg:px-14 py-2">
                    <div class="font-bold text-white lg:text-xl uppercase tracking-widest">SIZE</div>
                </div>
                <div class="gap-2 grid grid-cols-3 xl:grid-cols-4 my-10 px-6 lg:px-10 xl:px-14">
                    <div>
                        <input v-model="formSize.order" class="hidden" value="1. NEW ORDER" type="radio" name="size" :id="`new-order`">
                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`new-order`">
                            <div class="font-bold text-center text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">1. NEW ORDER</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="justify-self-center xl:col-span-2">
                        <input v-model="formSize.order" class="hidden" type="radio" value="2. REPEAT ORDER" name="size" :id="`repeat-order`">
                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`repeat-order`">
                            <div class="font-bold text-center text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">2. REPEAT ORDER</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input v-model="formSize.order" class="hidden" type="radio" name="size" value="3. GARMENT SAMPLE" :id="`garment-sample`">
                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`garment-sample`">
                            <div class="font-bold text-center text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">3. GARMENT SAMPLE</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                </div>
                <!-- body type -->
                <div class="gap-2 grid grid-cols-4 grid-rows-2 my-10 px-6 lg:px-10 xl:px-14">
                    <div class="max-xl:col-span-4 xl:row-span-2">
                        <div class="inline-block border-2 border-primary-50 px-2 pt-1.5 font-bold text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">BODY TYPE</div>
                    </div>
                    <div class="max-xl:col-span-2">
                        <input v-model="formSize.bodyType" value="2. SLIM" class="hidden" type="radio" name="body-type" :id="`slim`">
                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`slim`">
                            <div class="font-bold text-center text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">2. SLIM</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="max-xl:col-span-2">
                        <input v-model="formSize.bodyType" value="3. STANDARD I" class="hidden" type="radio" name="body-type" :id="`standard-1`">
                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`standard-1`">
                            <div class="font-bold text-center text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">3. STANDARD I</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="max-xl:col-span-2">
                        <input v-model="formSize.bodyType" value="4. STANDARD II" class="hidden" type="radio" name="body-type" :id="`standard-2`">
                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`standard-2`">
                            <div class="font-bold text-center text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">4. STANDARD II</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="max-xl:col-span-2">
                        <input v-model="formSize.bodyType" value="5. BIG I" class="hidden" type="radio" name="body-type" :id="`big-1`">
                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`big-1`">
                            <div class="font-bold text-center text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">5. BIG I</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="max-xl:col-span-2">
                        <input v-model="formSize.bodyType" value="7. BIG II" class="hidden" type="radio" name="body-type" :id="`big-2`">
                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`big-2`">
                            <div class="font-bold text-center text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">7. BIG II</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="max-xl:col-span-2">
                        <input v-model="formSize.bodyType" value="3. STANDARD II" class="hidden" type="radio" name="body-type" :id="`standard-3`">
                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`standard-3`">
                            <div class="font-bold text-center text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">3. STANDARD II</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                </div>

                <!-- sleeve -->
                <div class="gap-2 grid grid-cols-4 grid-rows-2 mt-6 px-6 lg:px-10 xl:px-14">
                    <div class="max-xl:col-span-4 xl:row-span-2">
                        <div class="inline-block border-2 border-primary-50 px-2 pt-1.5 font-bold text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">SLEEVE</div>
                    </div>
                    <div class="max-xl:col-span-2">
                        <input v-model="formSize.sleeve" value="1. SLIM SLEEVE" class="hidden" type="radio" name="sleeve" :id="`slim-sleeve`">
                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`slim-sleeve`">
                            <div class="font-bold text-center text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">1. SLIM SLEEVE</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="max-xl:col-span-2">
                        <input v-model="formSize.sleeve" value="2. REGULAR SLEEVE" class="hidden" type="radio" name="sleeve" :id="`regular-sleeve`">
                        <label class="flex items-center gap-4 px-2 rounded h-full cursor-pointer" :for="`regular-sleeve`">
                            <div class="font-bold text-center text-primary-50 text-xs 2xl:text-lg xl:text-base uppercase tracking-widest">2. REGULAR SLEEVE</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                </div>

                <div class="mt-4 px-6 lg:px-10 xl:px-14">
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
                                        <input v-model="formSize.shirt.neck" type="text" class="w-full font-roboto text-center">
                                    </td>
                                    <td>
                                        <input v-model="formSize.shirt.rightSleeve" type="text" class="w-full font-roboto text-center">
                                    </td>
                                    <td>
                                        <input v-model="formSize.shirt.leftSleeve" type="text" class="w-full font-roboto text-center">
                                    </td>
                                    <td>
                                        <input v-model="formSize.shirt.chest" type="text" class="w-full font-roboto text-center">
                                    </td>
                                    <td>
                                        <input v-model="formSize.shirt.waist" type="text" class="w-full font-roboto text-center">
                                    </td>
                                    <td>
                                        <input v-model="formSize.shirt.shoulder" type="text" class="w-full font-roboto text-center">
                                    </td>
                                </tr>
                                <tr class="*:border-2 *:border-primary-50 *:text-center">
                                    <td>ACTUAL</td>
                                    <td>
                                        <input v-model="formSize.actual.neck" type="text" class="w-full font-roboto text-center">
                                    </td>
                                    <td>
                                        <input v-model="formSize.actual.rightSleeve" type="text" class="w-full font-roboto text-center">
                                    </td>
                                    <td>
                                        <input v-model="formSize.actual.leftSleeve" type="text" class="w-full font-roboto text-center">
                                    </td>
                                    <td>
                                        <input v-model="formSize.actual.chest" type="text" class="w-full font-roboto text-center">
                                    </td>
                                    <td>
                                        <input v-model="formSize.actual.waist" type="text" class="w-full font-roboto text-center">
                                    </td>
                                    <td>
                                        <input v-model="formSize.actual.shoulder" type="text" class="w-full font-roboto text-center">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="grid grid-cols-2 xl:grid-cols-4 mt-10 mb-10 *:px-2 *:pt-2 *:pb-1 text-primary-50 tracking-widest whitespace-pre">
                        <div class="border-2 border-primary-50">SPECIAL ADJUSTMENT</div>
                        <div class="flex border-primary-50 border-y-2 border-r-2">
                            <div>NECK SIZE :</div>
                            <div>
                                <input v-model="formSize.sa.neckSize" type="text" class="w-full font-roboto text-center">
                            </div>
                        </div>
                        <div class="flex border-primary-50 border-y-2 border-r-2 max-xl:border-l-2">
                            <div>SHOULDER :</div>
                            <div>
                                <input v-model="formSize.sa.shoulder" type="text" class="w-full font-roboto text-center">
                            </div>
                        </div>
                        <div class="flex border-primary-50 border-y-2 border-r-2">
                            <div>BACK LENGTH :</div>
                            <div>
                                <input v-model="formSize.sa.backLength" type="text" class="w-full font-roboto text-center">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-32">
                <div class="flex justify-between items-center bg-primary-300 px-4 lg:px-14 py-2">
                    <div class="font-bold text-white lg:text-xl uppercase tracking-widest">ADDITIONAL NOTES</div>
                </div>
                <div class="gap-3 grid grid-cols-5 my-10 px-6 lg:px-10 xl:px-14">
                    <div class="col-span-3">
                        <textarea v-model="additionalNote" class="border-2 border-primary-50 p-2 w-full h-full font-roboto placeholder:font-josefin placeholder:tracking-widest placeholder-primary-50" name="" id="" placeholder="NOTE"></textarea>
                    </div>
                    <div class="space-y-2 col-span-2">
                        <input v-model="discount" type="number"  class="border-2 border-primary-50 px-4 pt-2 pb-1 w-full text-primary-50 number-input" placeholder="DISCOUNT"/>
                        <input v-model="price" type="number" class="border-2 border-primary-50 px-4 pt-2 pb-1 w-full text-primary-50 number-input" placeholder="RP" />
                        <div>
                            <button @click="basicAmount()" class="bg-secondary px-5 pt-3 pb-2 w-full text-center text-primary-50">APPLY PRICE</button>
                        </div>
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
        @apply flex justify-center items-center border-primary-50 border text-transparent size-7;
        background: transparent no-repeat center;
    }
</style>
