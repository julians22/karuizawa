<script setup>
    import { defineAsyncComponent, ref } from 'vue';
    import IncomingOrder from './includes/IncomingOrder.vue';

    const props = defineProps({
        csrf: String,
        user: Object,
        route_edit_profile: String,
        route_logout: String,
        api_booking_url: String,
    });

    const Layout = defineAsyncComponent(() => import('../../includes/Layout.vue'));
    const OrderHistory = defineAsyncComponent(() => import('./includes/OrderHistory.vue'));
    const incomingOrder = defineAsyncComponent(() => import('./includes/IncomingOrder.vue'));

    const FilterDialog = defineAsyncComponent(() => import('./utils/FilterDialog.vue'))

    const currentPage = ref('incoming-order')

    const childFilter = ref(null);
    const onClikFilter = () => {
        childFilter.value.dialog = true;
    }
</script>

<template>
    <FilterDialog ref="childFilter" />

    <Layout :route_edit_profile="route_edit_profile" :route_logout="route_logout" :user="user" :csrf="csrf" >
        <div class="flex justify-between items-center bg-primary-50 xl:px-14 lg:py-7 p-6">
            <div class="font-bold text-lg text-white lg:text-xl uppercase tracking-widest">CUSTOMER BOOKING</div>
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
        <div class="flex justify-between items-center bg-primary-100 xl:px-14 py-4 p-6">
            <div class="flex justify-between w-full">
                <div class="flex gap-5 text-white max-lg:text-sm tracking-wider">
                    <button :class="{ active: currentPage === 'incoming-order' }" @click="currentPage = 'incoming-order'">INCOMING ORDER</button>
                    <button :class="{ active: currentPage === 'order-history' }" @click="currentPage = 'order-history'">ORDER HISTORY</button>
                    <button >FITTING HISTORY</button>
                </div>
                <div @click="onClikFilter()">
                    <i class="text-white fa fa-filter fill-white" aria-hidden="true"></i>
                </div>
            </div>
        </div>

        <template v-if="currentPage === 'incoming-order'">
            <IncomingOrder />
        </template>

        <template v-if="currentPage === 'order-history'">
            <OrderHistory 
                :api_booking_url="api_booking_url" />
        </template>

        <div class="right-0 bottom-0 absolute flex">
            <button class="flex items-center gap-2 bg-primary-50 p-6 text-white tracking-widest">
                <span>NEXT PAGE</span>
                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
            </button>
            <button class="flex items-center gap-2 bg-secondary-50 p-6 text-white tracking-widest">
                <span>PAGE 1 of 100</span>
                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
            </button>
        </div>

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
