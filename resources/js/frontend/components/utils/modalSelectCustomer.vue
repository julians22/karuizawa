<script setup>
    import axios from 'axios';
    import { ref, defineExpose, computed, onMounted } from 'vue';

    const open = ref(false);

    const searchModel = ref(null);
    const searchTimeout = ref(null);
    const isLoading = ref(false);

    const options = ref([]);
    const customers = ref([]);

    const optionNull = ref(false);
    const customer = ref(null);
    const morePage = ref(1);

    const hasMorePage = ref(true);


    const loadMore = () => {
        morePage.value++;
        fetchCustomer(searchModel.value, morePage.value);
    }

    const customerIsNull = computed(() => {
        return customers.value.length === 0 ;
    });

    const fetchCustomer = async (keyword, page) => {
        try {
            const response = await axios.get(`/api/customer/search?keyword=${keyword}&page=${page}`);
            if (response.data.data.length > 0) {
                if (page === 1) {
                    customers.value = response.data.data;
                } else {
                    customers.value = [...customers.value, ...response.data.data];
                }


                optionNull.value = false;
            } else {
                optionNull.value = true;
            }
            hasMorePage.value = response.data.data.length < 10 ? false : true;
            isLoading.value = false;
        } catch (error) {
            console.log(error);
        }
    }

    const doSearch = () => {
        clearTimeout(searchTimeout.value);
        searchTimeout.value = setTimeout(() => {
            if (searchModel.value) {
                isLoading.value = true;
                fetchCustomer(searchModel.value, 1);
            }
        }, 300);
    };

    onMounted(() => {
        fetchCustomer('', 1);
    });

    const onSelected = (option) => {
        customer.value = option;
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
            class="z-[100] fixed inset-0 flex justify-center items-start bg-black bg-opacity-25 backdrop-blur-sm w-full h-full"
        >
            <div class="relative w-full max-w-2xl p-6 mt-20 bg-white rounded-2xl">
                <button class="absolute -top-5 -right-5" @click="open = false">
                    <img src="/img/icons/close.png" alt="">
                </button>
                <div class="relative">
                    <div class="absolute inset-y-0 flex items-center pointer-events-none ps-3 start-0">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input
                        type="search"
                        class="block w-full p-4 text-sm text-gray-900 border rounded-lg border-primary-50 bg-gray-50 focus:ring-1 focus:ring-primary-50 font-roboto focus:outline-none ps-10"
                        placeholder="search by Email or Name"
                        required
                        v-model="searchModel"
                        @keyup="doSearch"
                    />
                </div>
                <div v-if="isLoading" class="mt-5 tracking-widest text-center uppercase">loading...</div>
                <div
                    class="grid grid-cols-1 gap-2 mt-4 overflow-y-auto max-h-80 font-roboto" v-if="!isLoading && !customerIsNull">
                    <div @click="onSelected(item)" class="px-2 py-4 rounded cursor-pointer bg-secondary hover:bg-primary-50 hover:text-white" v-for="item in customers">
                        <div class="font-bold">{{ item.full_name }}</div>
                        <div class="text-xs">{{ item.email }} - ({{ item.phone }})</div>
                    </div>

                    <div v-if="hasMorePage" @click="loadMore" class="px-2 py-4 font-bold rounded cursor-pointer bg-primary-50 hover:bg-primary-100 hover:text-white">Load More</div>
                </div>
                <div v-if="customerIsNull && !isLoading" class="mt-5 tracking-widest text-center uppercase">data not found</div>
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
