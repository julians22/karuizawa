<script setup>
    import { defineAsyncComponent, ref } from 'vue';

    const props = defineProps({
        csrf: String,
        user: Object,
        route_edit_profile: String,
        route_logout: String
    });

    const Layout = defineAsyncComponent(() => import('../../utils/Layout.vue'));
    const OrderHistory = defineAsyncComponent(() => import('./includes/OrderHistory.vue'));


</script>

<template>
    <Layout :route_edit_profile="route_edit_profile" :route_logout="route_logout" :user="user" :csrf="csrf" >
        <div class="flex items-center justify-between py-7 px-14 bg-primary-50">
            <div class="text-2xl font-bold tracking-widest text-white uppercase">CUSTOMER BOOKING</div>
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
        <div class="flex items-center justify-between py-4 bg-primary-100 px-14">
            <div class="flex justify-between w-full">
                <div class="flex gap-5 tracking-wider text-white">
                    <div>INCOMING ORDER</div>
                    <div>ORDER HISTORY</div>
                    <div>FITTING HISTORY</div>
                </div>
                <div>
                    <i class="text-white fa fa-filter fill-white" aria-hidden="true"></i>
                </div>
            </div>
        </div>

        <OrderHistory />

        <div class="absolute bottom-0 right-0 flex">
            <button class="flex items-center gap-2 p-6 tracking-widest text-white bg-primary-50">
                <span>NEXT PAGE</span>
                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
            </button>
            <button class="flex items-center gap-2 p-6 tracking-widest text-white bg-secondary-50">
                <span>PAGE 1 of 100</span>
                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
            </button>
        </div>
    </Layout>
</template>
