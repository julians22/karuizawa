<script setup>
    import { defineEmits } from 'vue';

    const $emit = defineEmits(['btn-next']);

    const btnProcess = () => {
        $emit('btn-next', 'payment')
    }
</script>

<template>
    <div class="space-y-10">
        <section>
            <div class="flex items-center justify-between py-7 px-14 bg-primary-50">
                <div class="text-2xl font-bold tracking-widest text-white uppercase">Total shop</div>
                <form class="w-2/5">
                    <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                    <div class="relative">
                        <input type="search" id="default-search" class="block w-full px-4 py-2 text-sm text-gray-900 bg-white rounded-full pe-10" required />
                        <button type="submit" class="absolute inset-y-0 flex items-center end-0 pe-4">
                            <svg class="size-5 text-primary-50" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
            <div class="py-10 px-14">
                <table class="w-full">
                    <thead>
                            <th class="py-3 pr-6 text-left uppercase text-primary-50 ">Product</th>
                            <th class="px-6 py-3 text-center uppercase text-primary-50 ">Price</th>
                            <th class="px-6 py-3 text-center uppercase text-primary-50 ">qty</th>
                            <th class="px-6 py-3 text-center uppercase text-primary-50 ">Total</th>
                    </thead>
                    <tbody>
                        <tr class="border-b" v-for="i in 3">
                            <td class="py-3 pr-6 text-left text-primary-50 ">
                                <div class="text-[#606060]">KCS516-11M</div>
                                <div class="text-[#A3A3A3] text-sm">100304</div>
                            </td>
                            <td class="px-6 py-3 text-center text-primary-50 ">
                                <div class="text-lg text-center text-[#606060]">Rp 698.000,-</div>
                            </td>
                            <td class="px-6 py-3 text-center text-primary-50 ">
                                    <div class="flex justify-center w-full text-left number-input" data-controller="quantity">
                                        <button class="flex items-center p-2 font-bold no-underline border border-r-0 border-primary-50 bg-off-white text-grey-darker hover:bg-grey-lightest">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-3 h-3 fill-current"><path d="M424 318.2c13.3 0 24-10.7 24-24v-76.4c0-13.3-10.7-24-24-24H24c-13.3 0-24 10.7-24 24v76.4c0 13.3 10.7 24 24 24h400z"/></svg>
                                        </button>
                                        <input type="number" class="w-10 p-2 text-center border border-primary-50" value="1" data-target="quantity.value">

                                        <button class="flex items-center p-2 font-bold no-underline border border-l-0 border-primary-50 bg-off-white text-grey-darker hover:bg-grey-lightest">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-3 h-3 fill-current"><path d="M448 294.2v-76.4c0-13.3-10.7-24-24-24H286.2V56c0-13.3-10.7-24-24-24h-76.4c-13.3 0-24 10.7-24 24v137.8H24c-13.3 0-24 10.7-24 24v76.4c0 13.3 10.7 24 24 24h137.8V456c0 13.3 10.7 24 24 24h76.4c13.3 0 24-10.7 24-24V318.2H424c13.3 0 24-10.7 24-24z"/></svg>
                                        </button>
                                    </div>
                            </td>
                            <td class="px-6 py-3 text-center text-primary-50 ">
                                <div class="text-lg text-center text-secondary-50">Rp 698.000,-</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section>
            <div class="flex items-center justify-between py-7 px-14 bg-primary-50">
                <div class="text-2xl font-bold tracking-widest text-white uppercase">COUPON CODE & POINTS</div>
            </div>
            <div class="py-10 px-14">
                <div class="flex items-center gap-4">
                    <div class="tracking-widest text-[#606060] uppercase whitespace-pre">Coupon Code</div>
                    <div class="relative">
                        <span class="absolute top-0 bottom-0 border-primary-50 border-y border-r right-0 flex items-center justify-center w-10 pt-2 pl-2.5 pr-3 rounded-r-full pointer-events-none bg-secondary text-primary-50">
                            ▼
                        </span>
                        <select id="countries" class="bg-white *:text-[#606060] uppercase rounded-full border border-primary-50 focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5 pl-2.5 pr-10  before:bg-blue-400">
                            <option value="30%">30% off</option>
                            <option value="30%">30% off</option>
                            <option value="30%">30% off</option>
                            <option value="30%">30% off</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 flex">
                <button @click="btnProcess()" class="flex items-center gap-2 p-6 tracking-widest text-white bg-secondary-50">
                    <span>PROCEED TO PAYMENT</span>
                    <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
                </button>
            </div>
        </section>
    </div>
</template>


<style scoped>
.number-input input[type="number"] {
  appearance: textfield;
}

.number-input input[type="number"]::-webkit-inner-spin-button,
.number-input input[type="number"]::-webkit-outer-spin-button {
  appearance: none;
}
</style>
