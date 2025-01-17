<script setup>
    import { defineAsyncComponent, onMounted, ref, watch, computed } from 'vue';
    import { usePage } from '../../../store/page';
    import { useProducts } from '../../../store/product';
    import { useCustomer } from '../../../store/customer';

    const props = defineProps({
        csrf: String,
        user: Object,
        route_edit_profile: String,
        route_logout: String,
        api_store_order: String,
        api_product_url: String,
    });

    const Layout = defineAsyncComponent(() => import('../../includes/Layout.vue'));
    const Customer = defineAsyncComponent(() => import('../includes/CustomerData.vue'));
    const TotalShop = defineAsyncComponent(() => import('../includes/TotalShop.vue'));
    const Payment = defineAsyncComponent(() => import('../includes/Payment.vue'));
    const Rtw = defineAsyncComponent(() => import('./includes/Rtw.vue'));

    const storePage = usePage();
    const storeProducts = useProducts();
    const storeCustomer = useCustomer();

    const pageRtw = ref(null);
    const pagePayment = ref(null);

    const currentSection = ref('customer-data');

    const urlParams = new URLSearchParams(window.location.search);
    const url = new URL(window.location.href);

    onMounted(() => {
        if (urlParams.get('page') != null) {
            storePage.currentPage = urlParams.get('page');
        }else{
            storePage.currentPage = currentSection.value;
            storeProducts.setSlug = [];
            storeProducts.setProducts = [];
            storeProducts.semi_custom = [];
            storeCustomer.customer = null;
            storeProducts.coupon_rtw = 0;

        }
    });

    const btnNext = (section, data) => {
        url.searchParams.set('page', section);
        window.history.pushState(null, '', url.toString());
        storePage.currentPage = section;
    }

</script>

<template>
    <Layout :route_edit_profile="route_edit_profile" :route_logout="route_logout" :user="user" :csrf="csrf" >
            <template v-if="storePage.get == 'total-shop'">
                <TotalShop @btn-next="btnNext"/>
            </template>

            <template v-if="storePage.get == 'customer-data'">
                <Customer @btn-next="btnNext"/>
            </template>

            <template v-if="storePage.get == 'payment'">
                <Payment :api_store_order="api_store_order" @btn-next="btnNext"/>
            </template>

            <template v-if="storePage.get == 'products'">
                <Rtw @btn-next="btnNext" :api_product_url="api_product_url" ref="pageRtw"/>
            </template>
    </Layout>
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
