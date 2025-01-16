<script setup>
    import axios from 'axios';
    import { ref, defineExpose, computed, watch, reactive} from 'vue';
    import { useCustomer } from '../../store/customer';
    import lodash from 'lodash';
    const { throttle, find, pickBy } = lodash;

    const open = ref(false);

    const searchModel = ref(null);
    const searchTimeout = ref(null);
    const isLoading = ref(false);
    const options = ref([]);
    const optionNull = ref(false);
    const customer = ref(null);

    const doSearch = () => {
        clearTimeout(searchTimeout.value);
        searchTimeout.value = setTimeout(() => {
            if (searchModel.value) {
                isLoading.value = true;
                axios.get(`/api/customer/search`, {params:{search: searchModel.value}})
                    .then(response => {
                        options.value = response.data.data;
                        optionNull.value = response.data.data.length ? false : true;
                        isLoading.value = false;
                    })
                    .catch(error => {
                        console.log(error);
                    })
            }else{
                options.value = [];
                optionNull.value = false;
            }
        }, 300);
    };

    const onSelected = (option) => {
        customer.value = option;
        useCustomer().customer = option;
        open.value = false;
    }

    defineExpose({
        open,
        customer
    })
</script>

<template>
    <Transition>
        <dialog v-if="open"
            @click.self="open = false"
            class="fixed inset-0 z-[100] flex items-start justify-center w-full h-full bg-black bg-opacity-25 backdrop-blur-sm"
        >
            <div class="relative w-full max-w-2xl p-6 mt-20 bg-white rounded-2xl">
                <button class="absolute -top-5 -right-5" @click="open = false">
                    <img src="/img/icons/close.png" alt="">
                </button>
                <div class="relative">
                    <div class="absolute inset-y-0 flex items-center pointer-events-none start-0 ps-3">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input type="search" class="block w-full p-4 text-sm text-gray-900 border rounded-lg border-primary-50 ps-10 bg-gray-50 font-roboto" placeholder="Search Customers..." required
                        v-model="searchModel"
                        @keyup="doSearch"
                    />
                </div>
                <div v-if="isLoading" class="mt-5 tracking-widest text-center uppercase">loading...</div>
                <div class="grid grid-cols-1 gap-2 mt-4 overflow-y-auto max-h-80 font-roboto" v-if="options.length && !isLoading">
                    <div @click="onSelected(option)" class="px-2 py-4 rounded cursor-pointer bg-secondary hover:bg-primary-50 hover:text-white" v-for="(option, index) in options" :key="index">
                        <div class="font-bold">{{ option.full_name }}</div>
                        <div class="text-xs">{{ option.email }} - {{ option.phone }}</div>
                    </div>
                </div>
                <div v-if="optionNull && !isLoading" class="mt-5 tracking-widest text-center uppercase">data not found</div>
            </div>
        </dialog>
    </Transition>
</template>

<style scoped>
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}

:deep(.vue-select .menu) {
    @apply transition-all duration-300 ease-in-out;
    position: relative;
}
</style>
