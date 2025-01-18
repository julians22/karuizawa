<script setup>
import { defineAsyncComponent } from 'vue';

const Layout = defineAsyncComponent(() => import('../../includes/Layout.vue'));
const Payment = defineAsyncComponent(() => import('@frontend/components/user/payment/includes/Payment.vue'));

const props = defineProps({
    csrf: String,
    user: Object,
    route_edit_profile: String,
    route_logout: String,
    api_store_order: String,
    api_product_url: String,
    order: Object,
});


</script>

<template>
    <Layout :route_edit_profile="route_edit_profile" :route_logout="route_logout" :user="user" :csrf="csrf" >
        <Payment :api_store_order="api_store_order" :order="order" @btn-next="btnNext"/>
    </Layout>
</template>
