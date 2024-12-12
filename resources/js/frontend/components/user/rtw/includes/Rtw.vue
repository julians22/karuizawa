<script setup>
    import { defineEmits, reactive, ref } from 'vue';

    const form = reactive({
        shirtsSelected: [],
    })

    const formError = ref([]);

    const $emit = defineEmits(['btn-next']);
    const btnProcess = () => {
        if (form.shirtsSelected.length) {
            $emit('btn-next', 'total-shop')
        }else{
            formError.value = ['Please select at least one item']
            console.log(formError.value);
        }
    }

</script>

<template>
    <section class="pb-20">
        <div class="flex items-center justify-between py-7 px-14 bg-primary-50">
            <div class="text-2xl font-bold tracking-widest text-white uppercase">Ready to wear</div>
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
        <div class="flex justify-between py-7 px-14 bg-secondary-50 gap-[5%]">
            <div class="flex items-center w-full gap-4">
                <div class="tracking-widest text-white uppercase whitespace-pre">Sort by</div>
                <div class="relative w-full">
                    <span class="absolute top-0 bottom-0 right-0 flex items-center justify-center w-10 pt-2 pl-2.5 pr-3 rounded-r-full pointer-events-none bg-secondary text-primary-50">
                        ▼
                    </span>
                    <select id="countries" class="bg-white rounded-full focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 before:bg-blue-400">
                        <option selected></option>
                        <option value="user">User</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center w-full gap-4">
                <div class="tracking-widest text-white uppercase whitespace-pre">Color</div>
                <div class="relative w-full">
                    <span class="absolute top-0 bottom-0 right-0 flex items-center justify-center w-10 pt-2 pl-2.5 pr-3 rounded-r-full pointer-events-none bg-secondary text-primary-50">
                        ▼
                    </span>
                    <select id="countries" class="bg-white rounded-full focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 before:bg-blue-400">
                        <option selected></option>
                        <option value="user">User</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center w-full gap-4">
                <div class="tracking-widest text-white uppercase whitespace-pre">Size</div>
                <div class="relative w-full">
                    <span class="absolute top-0 bottom-0 right-0 flex items-center justify-center w-10 pt-2 pl-2.5 pr-3 rounded-r-full pointer-events-none bg-secondary text-primary-50">
                        ▼
                    </span>
                    <select id="countries" class="bg-white rounded-full focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 before:bg-blue-400">
                        <option selected></option>
                        <option value="user">User</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="container py-20">
            <div class="grid grid-cols-4 gap-20">
                <div v-for="i in 8">
                    <input class="hidden" v-model="form.shirtsSelected" :value="i" type="checkbox" :id="`check-round0${i}`">
                    <label class="flex flex-col items-center px-2 rounded cursor-pointer" :for="`check-round0${i}`">
                        <div class="text-[#606060] text-center">KCS516-11M</div>
                        <div class="text-[#A3A3A3] text-sm text-center">100304</div>
                        <div class="text-lg text-center text-secondary-50">Rp 698.000,-</div>
                        <span class="flex items-center justify-center text-transparent border-4 rounded-full size-10 border-primary-50 checkbox-inner"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 right-0 flex">
            <button class="flex items-center gap-2 p-6 tracking-widest text-white bg-primary-50">
                <span>ADD SEMI CUSTOM</span>
                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
            </button>
            <button @click="btnProcess()" class="flex items-center gap-2 p-6 tracking-widest text-white bg-secondary-50">
                <span>PROCESS</span>
                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
            </button>
        </div>
    </section>
</template>

<style scoped>
    input[type="checkbox"] + label span.checkbox-inner {
        @apply border-primary-50;
    }
    input[type="checkbox"]:checked + label span.checkbox-inner {
        @apply bg-primary-50 border-primary-50;
        color: #fff;
        background-image: url("data:image/svg+xml,%3C%3Fxml version='1.0' encoding='UTF-8'%3F%3E%3Csvg width='14px' height='10px' viewBox='0 0 14 10' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'%3E%3C!-- Generator: Sketch 59.1 (86144) - https://sketch.com --%3E%3Ctitle%3Echeck%3C/title%3E%3Cdesc%3ECreated with Sketch.%3C/desc%3E%3Cg id='Page-1' stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'%3E%3Cg id='ios_modification' transform='translate(-27.000000, -191.000000)' fill='%23FFFFFF' fill-rule='nonzero'%3E%3Cg id='Group-Copy' transform='translate(0.000000, 164.000000)'%3E%3Cg id='ic-check-18px' transform='translate(25.000000, 23.000000)'%3E%3Cpolygon id='check' points='6.61 11.89 3.5 8.78 2.44 9.84 6.61 14 15.56 5.05 14.5 4'%3E%3C/polygon%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        background-size: 14px 10px;
    }
    .checkbox-inner {
        @apply inline-block size-6 rounded-full border-2 border-primary-50;
        background: transparent no-repeat center;
    }

</style>
