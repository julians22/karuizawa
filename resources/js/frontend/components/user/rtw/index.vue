<script setup>
    import { defineAsyncComponent, onMounted, ref } from 'vue';

    const props = defineProps({
        csrf: String,
        user: Object,
        route_edit_profile: String,
        route_logout: String,
        api_product_url: String,
    });

    const Layout = defineAsyncComponent(() => import('../../utils/Layout.vue'));
    const Customer = defineAsyncComponent(() => import('../includes/CustomerData.vue'));
    const TotalShop = defineAsyncComponent(() => import('../includes/TotalShop.vue'));
    const Payment = defineAsyncComponent(() => import('../includes/Payment.vue'));
    const Rtw = defineAsyncComponent(() => import('./includes/Rtw.vue'));

    const selected = ref([]);

    const currentSection = ref('customer-data')

    const btnNext = (section, data) => {
        currentSection.value = section;

        if (section == 'total-shop') {
            selected.value = data;
            console.log(data);
        }
    }

</script>

<template>
    <Layout :route_edit_profile="route_edit_profile" :route_logout="route_logout" :user="user" :csrf="csrf" >
            <template v-if="currentSection == 'total-shop'">
                <TotalShop @btn-next="btnNext" :selected="selected"/>
            </template>

            <template v-if="currentSection == 'customer-data'">
                <Customer @btn-next="btnNext"/>
            </template>

            <template v-if="currentSection == 'payment'">
                <Payment @btn-next="btnNext" :selected="selected"/>
            </template>

            <template v-if="currentSection == 'rtw'">
                <Rtw @btn-next="btnNext" :api_product_url="api_product_url"/>
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
