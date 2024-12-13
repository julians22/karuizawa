<script setup>
    import { defineEmits, onMounted, reactive, ref } from 'vue';
    import { TailwindPagination } from 'laravel-vue-pagination';

    const form = reactive({
        shirtsSelected: [],
    })

    const props = defineProps({
        api_product_url: String,
    });

    const products = ref({});

    onMounted(() => {

        fetch(props.api_product_url)
            .then(response => response.json())
            .then(data => {
                products.value = data;
            })
            .catch(error => {
                console.error('There was an error!', error);
            });

    })

    const getResults = async (page = 1) => {
        fetch(`${props.api_product_url}?page=${page}`)
            .then(response => response.json())
            .then(data => {
                products.value = data;
            })
            .catch(error => {
                console.error('There was an error!', error);
            });
    }

    const formError = ref([]);

    const $emit = defineEmits(['btn-next']);
    const btnProcess = () => {
        if (form.shirtsSelected.length) {
            $emit('btn-next', 'total-shop', form.shirtsSelected);
        }else{
            formError.value = ['Please select at least one item']
            console.log(formError.value);
        }
    }

</script>

<template>
    <section class="pb-20">
        <div class="flex justify-between items-center bg-primary-50 px-14 py-7">
            <div class="font-bold text-2xl text-white uppercase tracking-widest">Ready to wear</div>
            <form class="w-2/5">
                <label for="default-search" class="mb-2 font-medium text-gray-900 text-sm dark:text-white sr-only">Search</label>
                <div class="relative">
                    <input type="search" id="default-search" class="block bg-white px-4 py-2 rounded-full w-full text-gray-900 text-sm pe-10" required />
                    <button type="submit" class="absolute inset-y-0 flex items-center end-0 pe-4">
                        <svg class="text-primary-50 size-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
        <div class="flex justify-between gap-[5%] bg-secondary-50 px-14 py-7">
            <div class="flex items-center gap-4 w-full">
                <div class="text-white uppercase tracking-widest whitespace-pre">Sort by</div>
                <div class="relative w-full">
                    <span class="top-0 right-0 bottom-0 absolute flex justify-center items-center bg-secondary pt-2 pr-3 pl-2.5 rounded-r-full w-10 text-primary-50 pointer-events-none">
                        ▼
                    </span>
                    <select id="countries" class="block bg-white before:bg-blue-400 p-2.5 focus:border-blue-500 rounded-full focus:ring-blue-500 w-full">
                        <option selected></option>
                        <option value="user">User</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-4 w-full">
                <div class="text-white uppercase tracking-widest whitespace-pre">Color</div>
                <div class="relative w-full">
                    <span class="top-0 right-0 bottom-0 absolute flex justify-center items-center bg-secondary pt-2 pr-3 pl-2.5 rounded-r-full w-10 text-primary-50 pointer-events-none">
                        ▼
                    </span>
                    <select id="countries" class="block bg-white before:bg-blue-400 p-2.5 focus:border-blue-500 rounded-full focus:ring-blue-500 w-full">
                        <option selected></option>
                        <option value="user">User</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-4 w-full">
                <div class="text-white uppercase tracking-widest whitespace-pre">Size</div>
                <div class="relative w-full">
                    <span class="top-0 right-0 bottom-0 absolute flex justify-center items-center bg-secondary pt-2 pr-3 pl-2.5 rounded-r-full w-10 text-primary-50 pointer-events-none">
                        ▼
                    </span>
                    <select id="countries" class="block bg-white before:bg-blue-400 p-2.5 focus:border-blue-500 rounded-full focus:ring-blue-500 w-full">
                        <option selected></option>
                        <option value="user">User</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="py-20 container">
            <div class="gap-20 grid grid-cols-4">
                <div v-for="product in products.data">
                    <input class="hidden" v-model="form.shirtsSelected" :value="product" type="checkbox" :id="`check-round0${product.id}`">
                    <label class="flex flex-col items-center px-2 rounded cursor-pointer" :for="`check-round0${product.id}`">
                        <div class="text-[#606060] text-center">{{ product.product_name }}</div>
                        <div class="text-[#A3A3A3] text-center text-sm">{{ product.sku }}</div>
                        <div class="text-center text-lg text-secondary-50">Rp {{ product.price }}</div>
                        <span class="flex justify-center items-center border-4 border-primary-50 rounded-full text-transparent checkbox-inner size-10"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="flex justify-center pb-20">
            <TailwindPagination
                v-if="products"
                :data="products"
                :limit="5"
                :keepLength="true"
                active-classes="bg-primary-50 text-white"
                @pagination-change-page="getResults"
            />
        </div>
        

        <div class="right-0 bottom-0 absolute flex">
            <button class="flex items-center gap-2 bg-primary-50 p-6 text-white tracking-widest">
                <span>ADD SEMI CUSTOM</span>
                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
            </button>
            <button @click="btnProcess()" class="flex items-center gap-2 bg-secondary-50 p-6 text-white tracking-widest">
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
        @apply border-primary-50 bg-primary-50;
        color: #fff;
        background-image: url("data:image/svg+xml,%3C%3Fxml version='1.0' encoding='UTF-8'%3F%3E%3Csvg width='14px' height='10px' viewBox='0 0 14 10' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'%3E%3C!-- Generator: Sketch 59.1 (86144) - https://sketch.com --%3E%3Ctitle%3Echeck%3C/title%3E%3Cdesc%3ECreated with Sketch.%3C/desc%3E%3Cg id='Page-1' stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'%3E%3Cg id='ios_modification' transform='translate(-27.000000, -191.000000)' fill='%23FFFFFF' fill-rule='nonzero'%3E%3Cg id='Group-Copy' transform='translate(0.000000, 164.000000)'%3E%3Cg id='ic-check-18px' transform='translate(25.000000, 23.000000)'%3E%3Cpolygon id='check' points='6.61 11.89 3.5 8.78 2.44 9.84 6.61 14 15.56 5.05 14.5 4'%3E%3C/polygon%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        background-size: 14px 10px;
    }
    .checkbox-inner {
        @apply inline-block border-2 border-primary-50 rounded-full size-6;
        background: transparent no-repeat center;
    }

</style>
