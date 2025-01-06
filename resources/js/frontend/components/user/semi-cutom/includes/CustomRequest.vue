<script setup>
    import { isNull } from 'lodash';
    import { ref, defineProps, watch, onMounted } from 'vue'

    const props = defineProps({
        dataCustomRequest: Object
    })

    const cleric = ref(null);
    const selectCleric = ref([]);

    const form = ref({
        collar: null,
        cuffs: null,
        frontBody: null,
        button: null,
        bodySnapButton: null,
        cleric: null,
        buttonHole: null,
        buttonThread: null,
        stitchThread: null,
        embroidery: null,
        interlining: null,
        sewingOption: null,
        tape: null
    });

    const buttonCode = ref({
        code1: null,
        code2: null,
        code3: null,
        code4: null
    });

    const embroidery = ref({
        position: null,
        color: null,
        fontType: null,
        initialName: {
            x: null,
            y: null,
            dot: null,
            1: null,
            2: null,
            3: null,
            4: null,
            5: null,
            6: null,
            7: null,
            8: null,
            9: null,
            10: null
        },
        longName: null,
        price: null
    });

    const tape = ref({
        price: 200000,
        collar: {
            1: null,
            2: null,
            3: null,
            4: null
        },
        lower: null
    });

    watch(tape.value, (items) => {
        // js condition string ''
        if (isNull(tape.value.lower) || tape.value.lower == '') {
            form.value.tape = null;
            return;
        }
        const data = {
            collar: `${items.collar[1]}${items.collar[2]}${items.collar[3]}${items.collar[4]}`,
            lower: items.lower,
            price: items.price
        }
        form.value.tape = data;
    });

    const dataPrice = ref(null);

    watch(form.value, (items) => {
        const groupedData = groupAndCountByPrice(items);
        dataPrice.value = groupedData;

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

    const fabricCode = ref({
        collar: {
            code1: null,
            code2: null,
            code3: null,
            code4: null
        }
    });

    const onChangeCleric = () => {
        selectCleric.value = [];
    };

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
                    <div class="flex" v-for="(collar, index) in dataCustomRequest.collar[0]" :key="index">
                        <input class="hidden"v-model="form.collar" :value="collar" type="radio" name="collar" :id="'collar-'+collar.slug">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="'collar-'+collar.slug">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ collar.name }}</div>
                        </label>
                    </div>
                    <div class="mt-2 mb-3 text-xs text-pink-ka font-roboto">*No Additional Charge for above items</div>
                </div>
                <div class="col-span-6 xl:col-span-4">
                    <div class="grid grid-cols-1 p-2 border 2xl:grid-cols-2 border-pink-ka">
                        <div class="flex" v-for="(collar50, index) in dataCustomRequest.collar[50]" :key="index">
                            <input class="hidden" type="radio" name="collar" v-model="form.collar" :value="collar50" :id="'collar-'+collar50.slug">
                            <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="'collar-'+collar50.slug">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ collar50.name }}</div>
                            </label>
                        </div>
                        <div class="self-end col-span-2 justify-items-end">
                            <div class="">
                                <input class="hidden" type="radio" name="collar-50" :id="`50rb`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`50rb`">
                                    <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka"> Rp 50.000 </div>
                                    <!-- <span class="checkbox-inner pink"></span> -->
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 p-2 border border-pink-ka">
                        <div class="col-span-3">
                            <div class="flex" v-for="(collar100, index) in dataCustomRequest.collar[100]" :key="index">
                                <input class="hidden" v-model="form.collar" :value="collar100" type="radio" name="collar" :id="`collar-${collar100.slug}`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`collar-${collar100.slug}`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ collar100.name }}</div>
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center col-span-2 mt-4 lg:gap-4 fabric-code max-xl:flex-wrap">
                            <label for="fabric-code" class="tracking-wider uppercase text-primary-50 whitespace-nowrap">fabric code (4 digit)</label>
                            <div class="flex font-roboto">
                                <input type="text" v-model="fabricCode.collar.code1" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border size-8 border-primary-50">
                                <input type="text" v-model="fabricCode.collar.code2" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                                <input type="text" v-model="fabricCode.collar.code3" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                                <input type="text" v-model="fabricCode.collar.code4" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                            </div>
                        </div>
                        <div class="self-end col-3 justify-items-end">
                            <div class="">
                                <input class="hidden" type="radio" name="collar-100" :id="`100rb`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`100rb`">
                                    <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka">Rp 100.000 </div>
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
                    <div class="flex" v-for="(cuffs, index) in dataCustomRequest.cuffs[0]" :key="index">
                        <input class="hidden" type="radio" name="cuffs" v-model="form.cuffs" :value="cuffs" :id="`cuffs-${cuffs.slug}`">
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
                            <div class="flex" v-for="(cuffs, index) in dataCustomRequest.cuffs[70]" :key="index">
                                <input class="hidden" v-model="form.cuffs" :value="cuffs" type="radio" name="cuffs" :id="`cuffs-${cuffs.slug}`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cuffs-${cuffs.slug}`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ cuffs.name }}</div>
                                </label>
                            </div>
                            <div class="self-end col-3 justify-items-end">
                                <div class="">
                                    <input class="hidden" type="radio" name="cuffs-70" :id="`cuffs-70rb`">
                                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cuffs-70rb`">
                                        <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka">Rp 70.000 </div>
                                        <!-- <span class="checkbox-inner pink"></span> -->
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 p-2 border border-pink-ka">
                            <div class="col-span-3">
                                <div class="flex" v-for="(cuffs, index) in dataCustomRequest.cuffs[100]" :key="index">
                                    <input class="hidden" v-model="form.cuffs" :value="cuffs" type="radio" name="cuffs" :id="`cuffs-${cuffs.slug}`">
                                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cuffs-${cuffs.slug}`">
                                        <span class="checkbox-inner"></span>
                                        <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ cuffs.name }}</div>
                                    </label>
                                </div>
                            </div>
                            <div class="flex items-center col-span-2 mt-4 lg:gap-8 fabric-code max-lg:flex-wrap">
                                <label for="fabric-code" class="tracking-wider uppercase text-primary-50 whitespace-nowrap">fabric code (4 digit)</label>
                                <div class="flex font-roboto">
                                    <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border size-8 border-primary-50">
                                    <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                                    <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                                    <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                                </div>
                            </div>
                            <div class="self-end cols-3 justify-items-end">
                                <div class="">
                                    <input class="hidden" type="radio" name="cuffs-100" :id="`cuffs-100rb`">
                                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cuffs-100rb`">
                                        <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka">Rp 100.000 </div>
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
                            <div class="flex" v-for="(frontBody, index) in dataCustomRequest.frontBody[100]" :key="index">
                                <input class="hidden" type="radio" name="front-body" :value="frontBody" v-model="form.frontBody"  :id="`hidden-placket`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`hidden-placket`">
                                    <span class="checkbox-inner"></span>
                                    <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ frontBody.name }}</div>
                                </label>
                            </div>
                            <div class="">
                                <input class="hidden" type="radio" name="front-bdy-100" :id="`front-bdy-100`">
                                <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`front-bdy-100`">
                                    <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka">Rp 100.000 </div>
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
                    <div v-for="(button, index) in dataCustomRequest.button[100]" :key="index">
                        <input class="hidden" type="radio" :value="button" v-model="form.button" name="button" :id="`btn-${button.slug}`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`btn-${button.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ button.name }}</div>
                        </label>
                    </div>
                    <div class="flex font-roboto">
                        <input v-model="buttonCode.code1" type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border size-8 border-primary-50">
                        <input v-model="buttonCode.code2" type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                        <input v-model="buttonCode.code3" type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                        <input v-model="buttonCode.code4" type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                    </div>
                    <div class="">
                        <input class="hidden" type="radio" name="button-100" :id="`button-100`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`button-100`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka">Rp 100.000</div>
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
                    <div v-for="bodySnapButton in dataCustomRequest.bodySnapButton" :key="bodySnapButton">
                        <input class="hidden" :value="bodySnapButton" v-model="form.bodySnapButton" type="radio" name="body-snap-button" :id="`${bodySnapButton.slug}`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`${bodySnapButton.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ bodySnapButton.name }}</div>
                        </label>
                    </div>
                    <div class="">
                        <input class="hidden" type="radio" name="bdy-snap-100" :id="`bdy-snap-50`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`bdy-snap-50`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka">Rp 50.000 </div>
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

             <div v-for="(dataCleric, index) in dataCustomRequest.cleric" :key="index"
                class="p-4 mx-6 mt-4 space-y-4 border xl:mx-14 border-pink-ka">
                <div v-for="(subCeleric, index) in dataCleric" :key="index" class="flex gap-2">
                    <div>
                        <input v-model="form.cleric" @change="onChangeCleric" class="hidden" type="radio" name="cleric"  :value="subCeleric" :id="`${subCeleric.slug}`">
                        <label class="grid items-center h-full grid-cols-2 gap-1 rounded cursor-pointer" :for="`${subCeleric.slug}`">
                            <div class="text-sm font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ subCeleric.no }}</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="flex gap-2 checkbox-cleric" :class="{ 'pointer-events-none': form.cleric?.slug != subCeleric.slug}">
                        <div v-for="clericItems in subCeleric.data" :key="cleric">
                            <input class="hidden" type="checkbox" name="clericItems" v-model="selectCleric" :value="clericItems" :id="`cleric-${subCeleric.no}-${clericItems.slug}`">
                            <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`cleric-${subCeleric.no}-${clericItems.slug}`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ clericItems.name }}</div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex self-end gap-2 justify-self-end">
                    <div class="flex flex-col col-span-2 gap-1 mt-4 fabric-code">
                            <label for="fabric-code" class="tracking-wider uppercase text-primary-50 whitespace-nowrap">fabric code (4 digit)</label>
                            <div class="flex font-roboto">
                                <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border size-8 border-primary-50">
                                <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                                <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                                <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                            </div>
                        </div>
                    <div class="self-end">
                        <input class="hidden" type="radio" name="cleric-50rb" :id="`cleric-50rb`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cleric-50rb`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka">{{ 'rp '+index+'.000' }}</div>
                        </label>
                    </div>
                </div>
            </div>

             <!-- <div class="p-4 mx-6 mt-4 space-y-4 border xl:mx-14 border-pink-ka">
                <div class="flex gap-2">
                    <div>
                        <input v-model="cleric" @change="onChangeCleric" class="hidden" type="radio" value="cleric-01" :id="`cleric-01`">
                        <label class="grid items-center h-full grid-cols-2 gap-1 rounded cursor-pointer" :for="`cleric-01`">
                            <div class="text-sm font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">1.</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="flex gap-2 checkbox-cleric" :class="{ 'pointer-events-none': cleric != 'cleric-01'}">
                        <div>
                            <input class="hidden" type="checkbox" value="collar 01" v-model="selectCleric" :id="`cleric-01-collar`">
                            <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`cleric-01-collar`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">collar</div>
                            </label>
                        </div>
                        <div>
                            <input class="hidden" type="checkbox" v-model="selectCleric" value="collar stand 01" :id="`cleric-01-collar-stand`">
                            <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`cleric-01-collar-stand`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">collar stand</div>
                            </label>
                        </div>
                        <div>
                            <input class="hidden" type="checkbox" v-model="selectCleric" value="cuffs 01" :id="`cleric-01-cuffs`">
                            <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`cleric-01-cuffs`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">cuffs</div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2">
                    <div>
                        <input v-model="cleric" @change="onChangeCleric" class="hidden" type="radio" value="cleric-02" :id="`cleric-02`">
                        <label class="grid items-center h-full grid-cols-2 gap-1 rounded cursor-pointer" :for="`cleric-02`">
                            <div class="text-sm font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">2.</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="flex gap-2 checkbox-cleric" :class="{ 'pointer-events-none': cleric != 'cleric-02'}">
                        <div>
                            <input class="hidden" type="checkbox" value="collar 02" v-model="selectCleric" :id="`cleric-02-collar`">
                            <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`cleric-02-collar`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">collar</div>
                            </label>
                        </div>
                        <div>
                            <input class="hidden" type="checkbox" v-model="selectCleric" value="collar stand 02" :id="`cleric-02-collar-stand`">
                            <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`cleric-02-collar-stand`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">collar stand</div>
                            </label>
                        </div>
                        <div>
                            <input class="hidden" type="checkbox" v-model="selectCleric" value="cuffs 02" :id="`cleric-02-cuffs`">
                            <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`cleric-02-cuffs`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">cuffs</div>
                            </label>
                        </div>
                        <div>
                            <input class="hidden" type="checkbox" v-model="selectCleric" value="front placket 02" :id="`cleric-02-front-placket`">
                            <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`cleric-02-front-placket`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">front placket</div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2">
                    <div>
                        <input v-model="cleric" @change="onChangeCleric" class="hidden" type="radio" value="cleric-03" :id="`cleric-03`">
                        <label class="grid items-center h-full grid-cols-2 gap-1 rounded cursor-pointer" :for="`cleric-03`">
                            <div class="text-sm font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">3.</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="flex gap-2 checkbox-cleric" :class="{ 'pointer-events-none': cleric != 'cleric-03'}">
                        <div>
                            <input class="hidden" type="checkbox" value="inner collar stand 03" v-model="selectCleric" :id="`cleric-02-inner-collar-stand`">
                            <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`cleric-02-inner-collar-stand`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">inner collar stand</div>
                            </label>
                        </div>
                        <div>
                            <input class="hidden" type="checkbox" v-model="selectCleric" value="inner cuffs 03" :id="`cleric-02-inner-cuffs`">
                            <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`cleric-02-inner-cuffs`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">inner cuffs</div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex self-end gap-2 justify-self-end">
                    <div class="flex flex-col col-span-2 gap-1 mt-4 fabric-code">
                            <label for="fabric-code" class="tracking-wider uppercase text-primary-50 whitespace-nowrap">fabric code (4 digit)</label>
                            <div class="flex font-roboto">
                                <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border size-8 border-primary-50">
                                <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                                <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                                <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                            </div>
                        </div>
                    <div class="self-end">
                        <input class="hidden" type="radio" name="cleric-50rb" :id="`cleric-50rb`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cleric-50rb`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka"> Rp 50.000 </div>
                        </label>
                    </div>
                </div>
             </div> -->

             <!-- <div class="p-4 mx-6 mt-4 space-y-4 border xl:mx-14 border-pink-ka">
                <div class="flex gap-2">
                    <div>
                        <input v-model="cleric" @change="onChangeCleric" class="hidden" type="radio" value="cleric-04" :id="`cleric-04`">
                        <label class="grid items-center h-full grid-cols-2 gap-1 rounded cursor-pointer" :for="`cleric-04`">
                            <div class="text-sm font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">4.</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="flex gap-2 checkbox-cleric" :class="{ 'pointer-events-none': cleric != 'cleric-04'}">
                        <div>
                            <input class="hidden" type="checkbox" value="inner collar stand 04" v-model="selectCleric" :id="`cleric-04-inner-collar-stand`">
                            <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`cleric-04-inner-collar-stand`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">inner collar stand</div>
                            </label>
                        </div>
                        <div>
                            <input class="hidden" type="checkbox" v-model="selectCleric" value="inner cuffs 04" :id="`cleric-01-inner-cuffs`">
                            <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`cleric-01-inner-cuffs`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">inner cuffs</div>
                            </label>
                        </div>
                        <div>
                            <input class="hidden" type="checkbox" v-model="selectCleric" value="lower placket 04" :id="`cleric-04-lower-placket`">
                            <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`cleric-04-lower-placket`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">lower placket</div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex self-end gap-2 justify-self-end">
                    <div class="flex flex-col col-span-2 gap-1 mt-4 fabric-code">
                        <label for="fabric-code" class="tracking-wider uppercase text-primary-50 whitespace-nowrap">fabric code (4 digit)</label>
                        <div class="flex font-roboto">
                            <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border size-8 border-primary-50">
                            <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                            <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                            <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                        </div>
                    </div>
                    <div class="self-end">
                        <input class="hidden" type="radio" name="cleric-04-50rb" :id="`cleric-04-100rb`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`cleric-04-100rb`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka"> Rp 100.000 </div>
                            <span class="checkbox-inner pink"></span>
                        </label>
                    </div>
                </div>
             </div> -->
         </div>

         <!-- button hole, button thread, stitch thread -->
         <div class="grid gap-2 mt-4 lg:grid-cols-2 xl:grid-cols-3 whitespace-nowrap">
            <!-- button hole -->
            <div class="max-lg:col-span-2">
                <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                    <div class="text-lg font-bold tracking-wider text-white uppercase">11. button hole</div>
                </div>

                <div class="grid grid-cols-2 gap-2 p-4 mx-6 mt-4 border lg:ml-6 xl:ml-14 lg:mr-0 border-pink-ka">
                    <div class="flex" v-for="(buttonHole, index) in dataCustomRequest.buttonHole[30]" :key="index">
                        <input class="hidden" type="radio" name="button-hole" :value="buttonHole" v-model="form.buttonHole"  :id="`botton-hole-${buttonHole.slug}`">
                        <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`botton-hole-${buttonHole.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ index + 1 }}. {{ buttonHole.name }}</div>
                        </label>
                    </div>

                    <div class="self-end col-span-2 justify-self-end">
                        <input class="hidden" type="radio" name="button-hole-30rb" :id="`button-hole-30rb`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`button-hole-30rb`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka"> Rp 30.000 </div>
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
                    <div class="flex" v-for="(buttonThread, index) in dataCustomRequest.buttonThread[30]" :key="index">
                        <input class="hidden" type="radio" name="button-thread" :value="buttonThread" v-model="form.buttonThread" :id="`botton-thread-${buttonThread.slug}`">
                        <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`botton-thread-${buttonThread.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ index + 1 }}. {{ buttonThread.name }}</div>
                        </label>
                    </div>

                    <div class="self-end col-span-2 justify-self-end">
                        <input class="hidden" type="radio" name="button-thread-30rb" :id="`button-thread-30rb`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`button-thread-30rb`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka"> Rp 30.000 </div>
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
                    <div class="flex" v-for="(stitchThread, index) in dataCustomRequest.stitchThread[30]" :key="index">
                        <input class="hidden" type="radio" name="stitch-thread" :value="stitchThread" v-model="form.stitchThread" :id="`stitch-thread-${stitchThread.slug}`">
                        <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`stitch-thread-${stitchThread.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ index + 1 }}. {{ stitchThread.name }}</div>
                        </label>
                    </div>

                    <div class="self-end col-span-2 justify-self-end">
                        <input class="hidden" type="radio" name="stitch-thread-30rb" :id="`stitch-thread-30rb`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`stitch-thread-30rb`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka"> Rp 30.000 </div>
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
                        <div class="flex" v-for="(embroideryPosition, index) in dataCustomRequest.embroidery.position" :key="index">
                            <input class="hidden" type="radio" name="embroidery-position" :value="embroideryPosition" v-model="embroidery.position" :id="`embroidery-position-${embroideryPosition.slug}`">
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
                            <div class="flex" v-for="(embroideryColor, index) in dataCustomRequest.embroidery.color" :key="index">
                                <input class="hidden" type="radio" name="embroidery-color" :value="embroideryColor" v-model="embroidery.color" :id="`embroidery-color-${embroideryColor.slug}`">
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
                        <div class="flex" v-for="(embroideryFontType, index) in dataCustomRequest.embroidery.fontType" :key="index">
                            <input class="hidden" type="radio" name="embroidery-font-type" :value="embroideryFontType" v-model="embroidery.fontType" :id="`embroidery-font-type-${embroideryFontType.slug}`">
                            <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`embroidery-font-type-${embroideryFontType.slug}`">
                                <span class="checkbox-inner"></span>
                                <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ +(index + 1)+'. '+embroideryFontType.name }}</div>
                            </label>
                        </div>
                    </div>

                    <div class="col-span-3 p-4">
                        <div class="flex items-end gap-4 max-xl:flex-wrap">
                            <div class="flex items-end font-roboto">
                                <input v-model="embroidery.initialName.x" type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border size-10 border-primary-50">
                                <input v-model="embroidery.initialName.dot" type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 size-5 border-y border-primary-50">
                                <input v-model="embroidery.initialName.y" type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-l border-r size-10 border-y border-primary-50">
                                <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50"
                                    v-for="i in 10"
                                    v-model="embroidery.initialName[i]">
                            </div>
                            <div class="flex w-full gap-4">
                                <input type="text" class="block w-full h-8 p-2 text-sm text-gray-900 border border-primary-50 font-roboto">
                                <div class="whitespace-nowrap">
                                    <input class="hidden" type="radio" name="embroidery-50rb" :id="`embroidery-50rb`">
                                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`embroidery-50rb`">
                                        <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka">{{ currencyFormat(dataCustomRequest.embroidery.price) }}</div>
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
            <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                <div class="text-lg font-bold tracking-wider text-white uppercase">15. interlining</div>
            </div>

            <div class="flex items-center justify-between mx-6 mt-4 xl:mx-14">
                <div class="p-4 border lg:w-1/2 border-pink-ka">
                    <div class="flex" v-for="(interlining, index) in dataCustomRequest.interlining[0]" :key="index">
                        <input class="hidden" type="radio" name="interlining" :value="interlining" v-model="form.interlining" :id="`interlining-${interlining.slug}`">
                        <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`interlining-${interlining.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ interlining.name }}</div>
                        </label>
                    </div>
                </div>
                <div class="flex items-center justify-between w-full p-4 border border-pink-ka">
                    <div v-for="(interlining, index) in dataCustomRequest.interlining[100]" :key="index">
                        <input class="hidden" type="radio" name="interlining" :value="interlining" v-model="form.interlining" :id="`interlining-${interlining.slug}`">
                        <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`interlining-${interlining.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ interlining.name  }}</div>
                        </label>
                    </div>
                    <div class="whitespace-nowrap">
                        <input class="hidden" type="radio" name="interlining-100rb" :id="`interlining-100rb`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`interlining-100rb`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka"> Rp 100.000 </div>
                            <!-- <span class="checkbox-inner pink"></span> -->
                        </label>
                    </div>
                </div>
            </div>
        </div>

         <div class="mt-6">
            <div class="flex items-center justify-between py-2 px-14 bg-blue-ka">
                <div class="text-lg font-bold tracking-wider text-white uppercase">15. sewing option</div>
            </div>

            <div class="flex flex-col items-center justify-between mx-6 mt-4 xl:mx-14">
                <div v-for="(sewing, index) in dataCustomRequest.sewingOption" :key="index" class="flex items-center justify-between w-full p-4 border border-pink-ka">
                    <div v-for="(sewingOption, index) in sewing" :key="index">
                        <input class="hidden" type="radio" name="sewing-option" :value="sewingOption" v-model="form.sewingOption" :id="`sewing-${sewingOption.slug}`">
                        <label class="flex items-center h-full gap-2 rounded cursor-pointer" :for="`sewing-${sewingOption.slug}`">
                            <span class="checkbox-inner"></span>
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-primary-50">{{ sewingOption.name }}</div>
                        </label>
                    </div>
                    <div class="whitespace-nowrap">
                        <input class="hidden" type="radio" name="sewing-100rb" :id="`sewing-100rb`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`sewing-100rb`">
                            <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka"> {{ currencyFormat(dataCustomRequest.sewingOption[index][0].price) }}</div>
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
                    <input type="text" v-model="tape.collar[1]" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border size-8 border-primary-50">
                    <input type="text" v-model="tape.collar[2]" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                    <input type="text" v-model="tape.collar[3]" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                    <input type="text" v-model="tape.collar[4]" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r size-8 border-y border-primary-50">
                </div>
                <div class="w-9/12">
                    <input type="text" v-model="tape.lower" class="block w-full h-8 p-2 text-sm text-gray-900 border border-primary-50">
                </div>
                <div class="whitespace-nowrap">
                    <input class="hidden" type="radio" name="sewing-200rb" :id="`sewing-200rb`">
                    <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`sewing-200rb`">
                        <div class="text-xs font-bold tracking-wider text-center uppercase xl:text-sm text-pink-ka"> {{ currencyFormat(tape.price) }} </div>
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
                    <textarea class="w-full h-full p-2 border placeholder:font-josefin font-roboto border-primary-50 placeholder-primary-50 placeholder:tracking-widest" name="" id="" placeholder="NOTE"></textarea>
                </div>
                <div class="col-span-2 space-y-2">
                    <input type="text"  class="w-full px-4 pt-2 pb-1 border border-pink-ka text-primary-50" placeholder="DISCOUNT"/>
                    <input type="text" class="w-full px-4 pt-2 pb-1 border border-pink-ka text-primary-50" placeholder="RP" />
                    <div>
                        <button class="w-full px-5 pt-3 pb-2 text-center text-pink-ka bg-secondary">APPLY PRICE</button>
                    </div>
                </div>
            </div>

            <div class="px-6 xl:px-14">
                <table class="text-sm uppercase text-primary-50">
                    <thead>
                        <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-50 *:text-center">
                            <th colspan="2">option</th>
                            <th>quantity</th>
                            <th>sub total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-50 *:text-center">
                            <td>a</td>
                            <td>30.000</td>
                            <td>{{ dataPrice?.[30000]?.quantity }}</td>
                            <td class="!text-left">{{ currencyFormat(dataPrice?.[30000]?.subTotal) }}</td>
                        </tr>
                        <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-50 *:text-center">
                            <td>b</td>
                            <td>50.000</td>
                            <td>{{ dataPrice?.[50000]?.quantity }}</td>
                            <td class="!text-left">{{ currencyFormat(dataPrice?.[50000]?.subTotal) }}</td>
                        </tr>
                        <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-50 *:text-center">
                            <td>c</td>
                            <td>70.000</td>
                            <td>{{ dataPrice?.[70000]?.quantity }}</td>
                            <td class="!text-left">{{ currencyFormat(dataPrice?.[70000]?.subTotal) }}</td>
                        </tr>
                        <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-50 *:text-center">
                            <td>d</td>
                            <td>100.000</td>
                            <td>{{ dataPrice?.[100000]?.quantity }}</td>
                            <td class="!text-left">{{ currencyFormat(dataPrice?.[100000]?.subTotal) }}</td>
                        </tr>
                        <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-50 *:text-center">
                            <td>e</td>
                            <td>200.000</td>
                            <td>{{ dataPrice?.[200000]?.quantity }}</td>
                            <td class="!text-left">{{ currencyFormat(dataPrice?.[200000]?.subTotal) }}</td>
                        </tr>
                        <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-50 *:text-center">
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
