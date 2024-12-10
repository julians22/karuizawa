<script setup>
    import { defineAsyncComponent, ref } from 'vue';

    const props = defineProps({
        csrf: String,
        user: Object,
        route_edit_profile: String,
        route_logout: String
    });

    const Layout = defineAsyncComponent(() => import('../../utils/Layout.vue'));
</script>

<template>
    <Layout :route_edit_profile="route_edit_profile" :route_logout="route_logout" :user="user" :csrf="csrf" :extends="true">
        <template #sidebar>
            <div class="h-full py-20 bg-primary-50">
                <div class="mx-[5%] xl:mx-[10%] 2xl:mx-[20%] font-roboto text-white">
                    <div>
                        <div class="text-4xl tracking-widest text-center uppercase font-josefin">ORDER SUMMARY</div>
                        <div class="w-4/6 h-0.5 bg-white mx-auto opacity-70 my-10"></div>
                        <div class="*:space-y-4">
                            <tr class="whitespace-nowrap">
                                <td>Fabric </td>
                                <td class="w-full text-center">:</td>
                                <td class="">E004 </td>
                            </tr>
                            <tr class="whitespace-nowrap">
                                <td>Collar</td>
                                <td class="w-full text-center">:</td>
                                <td class="">10. Stand</td>
                            </tr>
                            <tr class="whitespace-nowrap">
                                <td>Sleeve</td>
                                <td class="w-full text-center">:</td>
                                <td class="">Short Sleeve</td>
                            </tr>
                            <tr class="whitespace-nowrap">
                                <td>Body Type</td>
                                <td class="w-full text-center">:</td>
                                <td class="">
                                    <div>Front - 2. French Palette</div>
                                    <div>Back - 4. No Pleats</div>
                                    <div>Hem - Standard Hem</div>
                                </td>
                            </tr>
                            <tr class="whitespace-nowrap">
                                <td>Pocket </td>
                                <td class="w-full text-center">:</td>
                                <td class="">Round</td>
                            </tr>
                            <tr class="whitespace-nowrap">
                                <td>Button</td>
                                <td class="w-full text-center">:</td>
                                <td class="">
                                    <div>Button - Purple </div>
                                    <div>Type - None</div>
                                    <div>Fabric - None </div>
                                </td>
                            </tr>
                        </div>
                    </div>
                    <div class="mt-20">
                        <div class="text-4xl tracking-widest text-center uppercase font-josefin">ACTUAL</div>
                        <div class="w-4/6 h-0.5 bg-white mx-auto opacity-70 my-10"></div>
                        <div class="*:space-y-4">
                            <tr class="whitespace-nowrap">
                                <td>Order </td>
                                <td class="w-full text-center">:</td>
                                <td class="">New Order</td>
                            </tr>
                            <tr class="whitespace-nowrap">
                                <td>Body Type </td>
                                <td class="w-full text-center">:</td>
                                <td class="">2. Slim</td>
                            </tr>
                            <tr class="whitespace-nowrap">
                                <td>Sleeve </td>
                                <td class="w-full text-center">:</td>
                                <td class="">Regular Sleeve</td>
                            </tr>
                            <tr class="whitespace-nowrap">
                                <td>Neck </td>
                                <td class="w-full text-center">:</td>
                                <td class="">43</td>
                            </tr>
                            <tr class="whitespace-nowrap">
                                <td>Shirt’s Right Sleeve  </td>
                                <td class="w-full text-center">:</td>
                                <td class="">92</td>
                            </tr>
                            <tr class="whitespace-nowrap">
                                <td>Shirt’s Left Sleeve </td>
                                <td class="w-full text-center">:</td>
                                <td class="">89</td>
                            </tr>
                            <tr class="whitespace-nowrap">
                                <td>Shirt’s Chest </td>
                                <td class="w-full text-center">:</td>
                                <td class="">43</td>
                            </tr>
                            <tr class="whitespace-nowrap">
                                <td>Shirt’s Waist </td>
                                <td class="w-full text-center">:</td>
                                <td class="">92</td>
                            </tr>
                            <tr class="whitespace-nowrap">
                                <td>Shirt’s Shoulder </td>
                                <td class="w-full text-center">:</td>
                                <td class="">89</td>
                            </tr>
                            <tr class="whitespace-nowrap">
                                <td>Actual’s Right Sleeve  </td>
                                <td class="w-full text-center">:</td>
                                <td class="">43</td>
                            </tr>
                            <tr class="whitespace-nowrap">
                                <td>Actual’s Left Sleeve </td>
                                <td class="w-full text-center">:</td>
                                <td class="">43</td>
                            </tr>
                            <tr class="whitespace-nowrap">
                                <td>Actual’s Chest </td>
                                <td class="w-full text-center">:</td>
                                <td class="">43</td>
                            </tr>
                            <tr class="whitespace-nowrap">
                                <td>Actual’s Waist </td>
                                <td class="w-full text-center">:</td>
                                <td class="">43</td>
                            </tr>
                            <tr class="whitespace-nowrap">
                                <td>Actual’s Shoulder </td>
                                <td class="w-full text-center">:</td>
                                <td class="">93</td>
                            </tr>
                            <tr class="whitespace-nowrap">
                                <td>SA Neck Size  </td>
                                <td class="w-full text-center">:</td>
                                <td class="">-</td>
                            </tr>
                            <tr class="whitespace-nowrap">
                                <td>SA Shoulder  </td>
                                <td class="w-full text-center">:</td>
                                <td class="">-</td>
                            </tr>
                            <tr class="whitespace-nowrap">
                                <td>SA Back Length  </td>
                                <td class="w-full text-center">:</td>
                                <td class="">-</td>
                            </tr>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-white font-roboto">
                <div class="py-8 bg-secondary-50 px-[5%] xl:px-[10%] 2xl:px-[20%]">
                    <div class="*:space-y-4 font-bold">
                        <tr class="whitespace-nowrap">
                            <td>Base Price </td>
                            <td class="w-full text-center">:</td>
                            <td class="">1.089.000</td>
                        </tr>
                        <tr class="whitespace-nowrap">
                            <td>Option</td>
                            <td class="w-full text-center">:</td>
                            <td class="">150.000</td>
                        </tr>
                        <tr class="whitespace-nowrap">
                            <td>Discount</td>
                            <td class="w-full text-center">:</td>
                            <td class="">10%</td>
                        </tr>
                        <tr class="whitespace-nowrap">
                            <td>Total Price</td>
                            <td class="w-full text-center">:</td>
                            <td class=""></td>
                        </tr>
                    </div>
                </div>
                <div class="pt-3 pb-2 text-lg font-bold tracking-widest text-center uppercase bg-black lg:text-xl xl:text-2xl 2xl:text-3xl font-josefin">
                    IDR 1.138.200,-
                </div>
            </div>
        </template>


        <div class="relative min-h-svh">
            <div class="flex items-center justify-between py-7 px-14 bg-primary-50">
                <div class="text-2xl font-bold tracking-widest text-white uppercase">CUSTOM MADE SHIRT</div>
            </div>

            <!-- fabric code -->
            <div>
                <div class="flex items-center justify-between py-2 px-14 bg-primary-300">
                    <div class="text-xl font-bold tracking-widest text-white uppercase">01. FABRIC</div>
                </div>
                <div class="flex items-center gap-8 my-5 px-14 fabric-code">
                    <label for="fabric-code" class="tracking-widest uppercase text-primary-50 whitespace-nowrap">fabric code</label>
                    <div class="flex font-roboto">
                        <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-2 size-10 border-primary-50">
                        <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r-2 size-10 border-y-2 border-primary-50">
                        <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r-2 size-10 border-y-2 border-primary-50">
                        <input type="text" maxlength="1" class="block p-2 text-sm text-center text-gray-900 border-r-2 size-10 border-y-2 border-primary-50">
                    </div>
                    <input type="text" class="block w-full h-10 p-2 text-sm text-gray-900 border-2 border-r-2 border-primary-50 font-roboto">
                </div>
            </div>

            <!-- collar -->
            <div>
                <div class="flex items-center justify-between py-2 px-14 bg-primary-300">
                    <div class="text-xl font-bold tracking-widest text-white uppercase">02. COLLAR</div>
                </div>
                <div class="grid grid-cols-6 my-10 px-14">
                    <div>
                        <input class="hidden" type="radio" name="collar" :id="`regular`">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`regular`">
                            <img class="h-24" src="img/custom-shirt/collar/regular.png" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">1. regular</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="collar" :id="`short-point`">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`short-point`">
                            <img class="h-24" src="img/custom-shirt/collar/short-point.png" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">3. SHORT POINT</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="collar" :id="`semi-wide`">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`semi-wide`">
                            <img class="h-24" src="img/custom-shirt/collar/semi-wide.png" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">4. SEMI WIDE</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="collar" :id="`cutaway`">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`cutaway`">
                            <img class="h-24" src="img/custom-shirt/collar/cutaway.png" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">7. CUTAWAY</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="collar" :id="`button-down`">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`button-down`">
                            <img class="h-24" src="img/custom-shirt/collar/button-down.png" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">11. BUTTON DOWN</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="collar" :id="`short-button-down`">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`short-button-down`">
                            <img class="h-24" src="img/custom-shirt/collar/short-button-down.png" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">13. SHORT BUTTON DOWN</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                </div>
                <div class="flex items-center gap-12 mx-20 my-10">
                    <div class="text-xs font-bold tracking-widest uppercase xl:text-base 2xl:text-lg text-primary-50">OPTION NUMBER</div>
                    <div class="flex font-roboto">
                        <input type="number" class="block p-2 text-sm text-center text-gray-900 border-2 size-10 border-primary-50">
                        <input type="number" class="block p-2 text-sm text-center text-gray-900 border-r-2 size-10 border-y-2 border-primary-50">
                    </div>
                </div>
            </div>

            <!-- cuffs and  front body -->
            <div class="grid grid-cols-2 gap-4">
                <!-- cuffs -->
                <div>
                    <div class="flex items-center justify-between py-2 px-14 bg-primary-300">
                        <div class="text-xl font-bold tracking-widest text-white uppercase">03. CUFFS</div>
                    </div>
                    <div class="grid grid-cols-3 my-10 px-14">
                        <div>
                            <input class="hidden" type="radio" name="cuffs" :id="`round`">
                            <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`round`">
                                <img class="h-28" src="img/custom-shirt/cuffs/round.png" alt="">
                                <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">1. ROUND</div>
                                <span class="checkbox-inner"></span>
                            </label>
                        </div>
                        <div>
                            <input class="hidden" type="radio" name="cuffs" :id="`mitred`">
                            <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`mitred`">
                                <img class="h-28" src="img/custom-shirt/cuffs/mitred.png" alt="">
                                <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">2. MITRED</div>
                                <span class="checkbox-inner"></span>
                            </label>
                        </div>
                        <div>
                            <input class="hidden" type="radio" name="cuffs" :id="`short-sleeve`">
                            <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`short-sleeve`">
                                <img class="h-28" src="img/custom-shirt/cuffs/short-sleeve.png" alt="">
                                <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">SHORT SLEEVE</div>
                                <span class="checkbox-inner"></span>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center gap-12 mx-20 my-10">
                        <div class="text-xs font-bold tracking-widest uppercase xl:text-base 2xl:text-lg text-primary-50">OPTION NUMBER</div>
                        <div class="flex font-roboto">
                            <input type="number" class="block p-2 text-sm text-center text-gray-900 border-2 size-10 border-primary-50">
                            <input type="number" class="block p-2 text-sm text-center text-gray-900 border-r-2 size-10 border-y-2 border-primary-50">
                        </div>
                    </div>
                </div>
                <!-- front body -->
                <div>
                    <div class="flex items-center justify-between py-2 px-14 bg-primary-300">
                        <div class="text-xl font-bold tracking-widest text-white uppercase">04. FRONT BODY</div>
                    </div>
                    <div class="grid grid-cols-3 my-10 px-14">
                        <div>
                            <input class="hidden" type="radio" name="front-body" :id="`standard`">
                            <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`standard`">
                                <img class="h-28" src="img/custom-shirt/front-body/standard.png" alt="">
                                <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">1. STANDARD</div>
                                <span class="checkbox-inner"></span>
                            </label>
                        </div>
                        <div>
                            <input class="hidden" type="radio" name="front-body" :id="`french`">
                            <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`french`">
                                <img class="h-28" src="img/custom-shirt/front-body/french.png" alt="">
                                <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">2. FRENCH</div>
                                <span class="checkbox-inner"></span>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center gap-12 mx-20 my-10">
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
                     <div class="flex items-center justify-between py-2 px-14 bg-primary-300">
                         <div class="text-xl font-bold tracking-widest text-white uppercase">05. POCKET</div>
                     </div>
                     <div class="grid grid-cols-3 my-10 px-14">
                        <div>
                            <input class="hidden" type="radio" name="pocket" :id="`nopocket`">
                            <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`nopocket`">
                                <img class="h-28" src="img/custom-shirt/pocket/nopocket.png" alt="">
                                <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">1. NO POCKET</div>
                                <span class="checkbox-inner"></span>
                            </label>
                        </div>
                        <div>
                            <input class="hidden" type="radio" name="pocket" :id="`pocket-round`">
                            <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`pocket-round`">
                                <img class="h-28" src="img/custom-shirt/pocket/round.png" alt="">
                                <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">2. ROUND</div>
                                <span class="checkbox-inner"></span>
                            </label>
                        </div>
                        <div>
                            <input class="hidden" type="radio" name="pocket" :id="`pocket-mitred`">
                            <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`pocket-mitred`">
                                <img class="h-28" src="img/custom-shirt/pocket/mitred.png" alt="">
                                <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">3. MITRED</div>
                                <span class="checkbox-inner"></span>
                            </label>
                        </div>
                    </div>
                 </div>
                <!-- hem -->
                 <div>
                     <div class="flex items-center justify-between py-2 px-14 bg-primary-300">
                         <div class="text-xl font-bold tracking-widest text-white uppercase">06. HEM</div>
                     </div>
                     <div class="grid grid-cols-3 my-10 px-14">
                        <div>
                            <input class="hidden" type="radio" name="hem" :id="`hem-standard`">
                            <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`hem-standard`">
                                <img class="h-28" src="img/custom-shirt/hem/standard.png" alt="">
                                <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">STANDARD</div>
                                <span class="checkbox-inner"></span>
                            </label>
                        </div>
                        <div>
                            <input class="hidden" type="radio" name="hem" :id="`straight-bottom-hem`">
                            <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`straight-bottom-hem`">
                                <img class="h-28" src="img/custom-shirt/hem/straight-bottom-hem.png" alt="">
                                <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">STRAIGHT BOTTOM HEM</div>
                                <span class="checkbox-inner"></span>
                            </label>
                        </div>
                    </div>
                 </div>
            </div>

            <!-- back body -->
            <div>
                <div class="flex items-center justify-between py-2 px-14 bg-primary-300">
                    <div class="text-xl font-bold tracking-widest text-white uppercase">07. BACK BODY</div>
                </div>
                <div class="grid grid-cols-4 my-10 px-14">
                    <div>
                        <input class="hidden" type="radio" name="back-body" :id="`side-tuck`">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`side-tuck`">
                            <img class="h-28" src="img/custom-shirt/back-body/side-tuck.png" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">1. SIDE TUCK</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="back-body" :id="`box-pleats`">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`box-pleats`">
                            <img class="h-28" src="img/custom-shirt/back-body/box-pleats.png" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">2. BOX PLEATS</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="back-body" :id="`side-darts`">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`side-darts`">
                            <img class="h-28" src="img/custom-shirt/back-body/side-darts.png" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">3. SIDE DARTS</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="back-body" :id="`nopleats`">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`nopleats`">
                            <img class="h-28" src="img/custom-shirt/back-body/nopleats.png" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">4. NO PLEATS</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- button -->
            <div>
                <div class="flex items-center justify-between py-2 px-14 bg-primary-300">
                    <div class="text-xl font-bold tracking-widest text-white uppercase">08. BUTTON</div>
                </div>
                <div class="grid grid-cols-9 my-10 px-14">
                    <div>
                        <input class="hidden" type="radio" name="button" :id="`white`">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`white`">
                            <img class="h-28" src="img/custom-shirt/button/white.png" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">1. WHITE</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="button" :id="`purple`">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`purple`">
                            <img class="h-28" src="img/custom-shirt/button/purple.png" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">2. PURPLE</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="button" :id="`brown`">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`brown`">
                            <img class="h-28" src="img/custom-shirt/button/brown.png" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">3. BROWN</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="button" :id="`grey`">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`grey`">
                            <img class="h-28" src="img/custom-shirt/button/grey.png" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">4. GREY</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="button" :id="`dark-grey`">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`dark-grey`">
                            <img class="h-28" src="img/custom-shirt/button/dark-grey.png" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">5. DARK GREY</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="button" :id="`wine-red`">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`wine-red`">
                            <img class="h-28" src="img/custom-shirt/button/wine-red.png" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">6. WINE RED</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="button" :id="`saxe`">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`saxe`">
                            <img class="h-28" src="img/custom-shirt/button/saxe.png" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">7. SAXE</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="button" :id="`pink`">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`pink`">
                            <img class="h-28" src="img/custom-shirt/button/pink.png" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">8. PINK</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="button" :id="`navy`">
                        <label class="flex flex-col items-center justify-between h-full gap-4 px-2 rounded cursor-pointer" :for="`navy`">
                            <img class="h-28" src="img/custom-shirt/button/navy.png" alt="">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">9. NAVY</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- size -->
            <div>
                <div class="flex items-center justify-between py-2 px-14 bg-primary-300">
                    <div class="text-xl font-bold tracking-widest text-white uppercase">SIZE</div>
                </div>
                <div class="grid grid-cols-4 gap-2 my-10 px-14">
                    <div>
                        <input class="hidden" type="radio" name="size" :id="`new-order`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`new-order`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">1. NEW ORDER</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div class="col-span-2 justify-self-center">
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
                <div class="grid grid-cols-4 grid-rows-2 gap-2 px-14">
                    <div class="row-span-2">
                        <div class="inline-block pt-1.5 px-2 text-xs font-bold tracking-widest uppercase border-2 xl:text-base 2xl:text-lg text-primary-50 border-primary-50">BODY TYPE</div>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="body-type" :id="`slim`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`slim`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">2. SLIM</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="body-type" :id="`standard-1`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`standard-1`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">3. STANDARD I</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="body-type" :id="`standard-2`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`standard-2`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">4. STANDARD II</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="body-type" :id="`big-1`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`big-1`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">5. BIG I</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="body-type" :id="`big-2`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`big-2`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">7. BIG II</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="body-type" :id="`standard-3`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`standard-3`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">3. STANDARD II</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                </div>

                <!-- sleeve -->
                <div class="grid grid-cols-4 grid-rows-2 gap-2 mt-6 px-14">
                    <div class="row-span-2">
                        <div class="inline-block pt-1.5 px-2 text-xs font-bold tracking-widest uppercase border-2 xl:text-base 2xl:text-lg text-primary-50 border-primary-50">SLEEVE</div>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="sleeve" :id="`slim-sleeve`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`slim-sleeve`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">1. SLIM SLEEVE</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                    <div>
                        <input class="hidden" type="radio" name="sleeve" :id="`regular-sleeve`">
                        <label class="flex items-center h-full gap-4 px-2 rounded cursor-pointer" :for="`regular-sleeve`">
                            <div class="text-xs font-bold tracking-widest text-center uppercase xl:text-base 2xl:text-lg text-primary-50">2. REGULAR SLEEVE</div>
                            <span class="checkbox-inner"></span>
                        </label>
                    </div>
                </div>

                <div class="px-14">
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

                    <div class="grid grid-cols-4 mt-10 mb-10 tracking-widest text-primary-50 *:pt-2 *:pb-1 *:px-2 whitespace-pre">
                        <div class="border-2 border-primary-50">SPECIAL ADJUSTMENT</div>
                        <div class="flex border-r-2 border-y-2 border-primary-50">
                            <div>NECK SIZE :</div>
                            <div>
                                <input type="text" class="w-full text-center font-roboto">
                            </div>
                        </div>
                        <div class="flex border-r-2 border-y-2 border-primary-50">
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
                <div class="flex items-center justify-between py-2 px-14 bg-primary-300">
                    <div class="text-xl font-bold tracking-widest text-white uppercase">ADDITIONAL NOTES</div>
                </div>
                <div class="grid grid-cols-5 gap-3 my-10 px-14">
                    <div class="col-span-3">
                        <textarea class="w-full h-full p-2 border-2 placeholder:font-josefin font-roboto border-primary-50 placeholder-primary-50 placeholder:tracking-widest" name="" id="" placeholder="NOTE"></textarea>
                    </div>
                    <div class="col-span-2 space-y-2">
                        <div class="w-full px-4 pt-2 pb-1 border-2 border-primary-50 text-primary-50">DISCOUNT </div>
                        <div class="w-full px-4 pt-2 pb-1 border-2 border-primary-50 text-primary-50">RP </div>
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
            <button @click="btnProcess()" class="flex items-center gap-2 p-6 tracking-widest text-white bg-secondary-50">
                <span>SUBMIT</span>
                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
            </button>
        </div>
    </Layout>
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
        @apply flex items-center justify-center text-transparent border-2 size-10 border-primary-50;
        background: transparent no-repeat center;
    }
</style>
