<script setup>
    import { defineAsyncComponent, reactive, ref, watch } from 'vue';

    const props = defineProps({
        csrf: String,
        user: Object,
        route_edit_profile: String,
        route_my_target: String,
        route_logout: String,
        api_booking_url: String,
        api_incoming_url: String,
        api_fitting_url: String,
        api_set_handling: String,
        api_set_status: String
    });

    const Layout = defineAsyncComponent(() => import('../../includes/Layout.vue'));
    const OrderHistory = defineAsyncComponent(() => import('./includes/OrderHistory.vue'));
    const IncomingOrder = defineAsyncComponent(() => import('./includes/IncomingOrder.vue'));
    const FittingHistory = defineAsyncComponent(() => import('./includes/FittingHistory.vue'));
    const PrintPerDay = defineAsyncComponent(() => import('./includes/PrintPerDay.vue'));

    const FilterDialog = defineAsyncComponent(() => import('./utils/FilterDialog.vue'));

    const incomingOrderRef = ref();
    const orderHistoryRef = ref();
    const fittingHistoryRef = ref();

    const currentPage = ref('order-history');

    const childFilter = ref({
        dialog: false,
        date: '',
        status: ''
    });

    const keyword = ref('');

    watch(currentPage, (value) => {
        filterData.date = '';
        filterData.status = '';
        filterData.keyword = '';

        childFilter.value.date = '';
        childFilter.value.status = '';
        childFilter.value.keyword = '';
    });

    const filterData = reactive({
        date: '',
        status: '',
        keyword: ''
    });

    const onClikFilter = () => {
        childFilter.value.dialog = true;
    }

    const updateFilter = () => {
        filterData.date = childFilter.value.date;
        filterData.status = childFilter.value.status;
        childFilter.value.dialog = false;

        if (currentPage.value === 'incoming-order') {
            incomingOrderRef.value.getBookings();
        }else if (currentPage.value === 'order-history') {
            orderHistoryRef.value.getBookings();
        }else if (currentPage.value === 'fitting-history') {
            fittingHistoryRef.value.getBookings()
        }
    }

    const applyKeyword = () => {
        filterData.keyword = keyword.value;

        if (currentPage.value === 'incoming-order' && filterData.keyword) {
            incomingOrderRef.value.getBookings();
        }else if (currentPage.value === 'order-history' && filterData.keyword){
            orderHistoryRef.value.getBookings();
        }else if (currentPage.value === 'fitting-history') {
            fittingHistoryRef.value.getBookings()
        }
    }

    const resetFilter = () => {
        filterData.date = '';
        filterData.status = '';
        filterData.keyword = '';

        keyword.value = '';

        if (currentPage.value === 'incoming-order') {
            incomingOrderRef.value.getBookings();
        }else if (currentPage.value === 'order-history') {
            orderHistoryRef.value.getBookings();
        }else if (currentPage.value === 'fitting-history') {
            fittingHistoryRef.value.getBookings()
        }
    }

</script>

<template>
    <FilterDialog
        @update:dialog="updateFilter"
        @reset:dialog="resetFilter"
        ref="childFilter" />

    <Layout :route_edit_profile="route_edit_profile" :route_my_target="route_my_target" :route_logout="route_logout" :user="user" :csrf="csrf" >
        <div class="flex justify-between items-center bg-primary-50 p-6 xl:px-14 lg:py-7">
            <div class="font-bold text-white text-lg lg:text-xl uppercase tracking-widest">CUSTOMER BOOKING</div>
            <div class="w-2/5">
                <label for="default-search" class="sr-only mb-2 font-medium text-gray-900 dark:text-white text-sm">Search</label>
                <div class="relative">
                    <input
                        v-model="keyword"
                        type="search" id="default-search" class="block bg-white px-4 py-2 pe-10 rounded-full w-full text-gray-900 text-sm"/>
                    <button @click="applyKeyword"
                        type="submit" class="absolute inset-y-0 flex items-center pe-4 end-0">
                        <svg class="size-5 text-primary-50" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="flex justify-between items-center bg-primary-100 p-6 xl:px-14 py-4">
            <div class="flex justify-between w-full">
                <div class="flex flex-wrap gap-5 text-white max-lg:text-sm tracking-wider">
                    <!-- <button :class="{ active: currentPage === 'incoming-order' }" @click="currentPage = 'incoming-order'">INCOMING ORDER</button> -->
                    <button :class="{ active: currentPage === 'order-history' }" @click="currentPage = 'order-history'">ORDER HISTORY</button>
                    <button :class="{ active: currentPage === 'fitting-history'}" @click="currentPage = 'fitting-history'">FITTING HISTORY</button>
                    <button :class="{ active: currentPage === 'print-per-day'}" @click="currentPage = 'print-per-day'">PRINT PER DAY</button>
                </div>
                <div class="flex items-center gap-5">

                    <div v-if="filterData.date || filterData.status || filterData.keyword">
                        <ul class="flex flex-wrap items-center gap-2">
                            <strong class="text-white">Filter Applied:</strong>
                            <li v-if="filterData.date"><small class="font-serif text-primary-50 text-sm"><span class="bg-secondary px-2 py-2">Date: {{ filterData.date }}</span></small></li>
                            <li v-if="filterData.status"><small class="font-serif text-primary-50 text-sm"><span class="bg-secondary px-2 py-2">Status: {{ filterData.status }}</span></small></li>
                            <li v-if="filterData.keyword"><small class="font-serif text-primary-50 text-sm"><span class="bg-secondary px-2 py-2">Keyword: {{ filterData.keyword }}</span></small></li>

                            <li @click="resetFilter" class="cursor-pointer"><small class="font-serif text-red-950 text-sm"><span class="bg-secondary px-2 py-2">Reset Filter</span></small></li>
                        </ul>
                    </div>

                    <div @click="onClikFilter()">
                        <span>
                            <i class="fill-white text-white fa fa-filter" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <template v-if="currentPage === 'order-history'">
            <OrderHistory
                ref="orderHistoryRef"
                :api_booking_url="api_booking_url"
                :filterData="filterData"
                :user="user"
                />
        </template>

        <template v-if="currentPage === 'fitting-history'">
            <FittingHistory
                ref="fittingHistoryRef"
                :api_fitting_url="api_fitting_url"
                :api_set_handling="api_set_handling"
                :filterData="filterData"
                :api_set_status="api_set_status"
                :user="user"
            />
        </template>

        <template v-if="currentPage === 'print-per-day'">
            <PrintPerDay />
        </template>

    </Layout>
</template>


<style scoped>
    .active {
        @apply relative;

        &::before {
            content: '';
            @apply bottom-0 left-0 absolute bg-white opacity-70 w-full h-0.5;
        }
    }
</style>
