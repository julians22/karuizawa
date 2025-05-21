<script setup>
import { defineAsyncComponent } from 'vue';

const Layout = defineAsyncComponent(() => import('../../includes/Layout.vue'));
const Payment = defineAsyncComponent(() => import('@frontend/components/user/payment/includes/Payment.vue'));

const props = defineProps({
    csrf: String,
    user: Object,
    route_edit_profile: String,
    route_my_target: String,
    route_logout: String,
    order: Object,
    route_payment: String,
    booking_route: String,
});


</script>

<template>
    <Layout :route_edit_profile="route_edit_profile" :route_my_target="route_my_target" :route_logout="route_logout" :user="user" :csrf="csrf" >
        <Payment
            :route_payment="route_payment"
            :order="order"
            :user="user"
            :booking_route="booking_route"
            />
    </Layout>
</template>
