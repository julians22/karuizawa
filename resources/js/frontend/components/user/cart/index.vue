
<script setup>
    import { defineAsyncComponent } from 'vue';

    const Layout = defineAsyncComponent(() => import('../../includes/Layout.vue'));
    const Cart = defineAsyncComponent(() => import('./includes/Cart.vue'));

    const props = defineProps({
        csrf: String,
        user: Object,
        route_edit_profile: String,
        route_my_target: String,
        route_logout: String,
        data_custom_shirt: Object,
        data_custom_request: Object,
        data_semi_custom: Object,
        api_store_order: String,
        coupons: Object
    });

    const btnNext = (section, data) => {
        console.log(section);
    }

</script>

<template>
    <Layout :route_edit_profile="route_edit_profile" :route_my_target="route_my_target" :route_logout="route_logout" :user="user" :csrf="csrf">
        <Cart @btn-next="btnNext" :coupons="coupons"
            :user="user" :api_store_order="api_store_order"/>
    </Layout>
</template>
