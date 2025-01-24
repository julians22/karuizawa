<script setup>
    import { defineAsyncComponent, ref, watch, computed, onMounted } from 'vue';
    import moment from "moment";

    const props = defineProps({
        data_semi_custom: Object,
        data_config: Object
    });

    const CustomBasic = defineAsyncComponent(() => import('./includes/CustomBasic.vue'));
    const CustomOptions = defineAsyncComponent(() => import('./includes/CustomOptions.vue'));

    const childBasic = ref();
    const childOption = ref();


    const amount = ref({
        basic: {},
        option: {}
    });

    const additionalBasic = (price) => {
        amount.value.basic = price;
    };

    const additionalOption = (price) => {
        amount.value.option = price;
    };

    const split = (string) => {
        return string.split('');
    }
</script>

<template>
    <div class="printable min-w-[1900px]">
        <div class="w-full py-3 mb-2 bg-secondary">
            <div class="flex items-center justify-between mx-20 text-secondary-50">
                <div>
                    <img class="w-60" src="/img/brand/logo-01.png" alt="logo">
                </div>
                <div class="text-base text-center uppercase">
                    <div class="">Pattern order shirt (for men)</div>
                    <div class="h-[1px] bg-secondary-50 mb-0.5"></div>
                    <div class="text-sm tracking-widest">Order Form</div>
                </div>
                <div>
                    <div class="text-right uppercase">
                        <div class="text-sm">Order No : {{ props.data_semi_custom.order_items[0].order.order_number }}</div>
                        <div class="text-sm">
                            <span>Order Date : {{ moment(props.data_semi_custom.order_items[0].order.created_at).format('DD/MM/YYYY') }}</span>
                            <span> | </span>
                            <span>
                                Option
                                <div class="inline-block">
                                    <input :checked="props.data_semi_custom.option_total > 0" class="hidden" type="radio" :id="`options`">
                                    <label class="flex items-center h-full rounded cursor-pointer" :for="`options`">
                                        <!-- <div class="label-name">1. SLIM SLEEVE</div> -->
                                        <span class="checkbox-inner"></span>
                                    </label>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <CustomBasic @additionalBasic="additionalBasic" :dataConfig="props.data_config" :dataSemiCustom="props.data_semi_custom" ref="childBasic" />
        <div style="page-break-before: always;"></div>
        <div class="w-full py-3 mb-2 bg-secondary">
            <div class="flex items-center justify-between mx-20 text-secondary-50">
                <div>
                    <img class="w-60" src="/img/brand/logo-01.png" alt="logo">
                </div>
                <div class="text-base text-center uppercase">
                    <div class="">Pattern order shirt (for men)</div>
                    <div class="h-[1px] bg-secondary-50 mb-0.5"></div>
                    <div class="text-sm tracking-widest">Order Form</div>
                </div>
                <div>
                    <div class="text-right uppercase">
                        <div class="text-sm">Order No : {{ props.data_semi_custom.order_items[0].order.order_number }}</div>
                        <div class="text-sm ">
                            <span>
                                fabric code:
                                <div class="inline-flex">
                                    <div class="box-input-wrapper">
                                        <input
                                            v-for="(digit, index) in split(props.data_semi_custom.basic_form.fabric.fabricCode)"
                                            type="text"
                                            maxlength="1"
                                            :value="digit"
                                            class="box-input print:text-lg"
                                        >
                                    </div>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <CustomOptions @additionalOption="additionalOption" :dataConfig="props.data_config" :dataSemiCustom="props.data_semi_custom" ref="childOption"/>
    </div>
</template>

<style scoped>
input[type="radio"]:checked + label span.checkbox-inner {
        @apply border-secondary-50 bg-primary-50;
        color: #fff;
        background-image: url("data:image/svg+xml,%3C%3Fxml version='1.0' encoding='UTF-8'%3F%3E%3Csvg width='14px' height='10px' viewBox='0 0 14 10' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'%3E%3C!-- Generator: Sketch 59.1 (86144) - https://sketch.com --%3E%3Ctitle%3Echeck%3C/title%3E%3Cdesc%3ECreated with Sketch.%3C/desc%3E%3Cg id='Page-1' stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'%3E%3Cg id='ios_modification' transform='translate(-27.000000, -191.000000)' fill='%23FFFFFF' fill-rule='nonzero'%3E%3Cg id='Group-Copy' transform='translate(0.000000, 164.000000)'%3E%3Cg id='ic-check-18px' transform='translate(25.000000, 23.000000)'%3E%3Cpolygon id='check' points='6.61 11.89 3.5 8.78 2.44 9.84 6.61 14 15.56 5.05 14.5 4'%3E%3C/polygon%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        background-size: 14px 10px;
    }
    .checkbox-inner {
        @apply flex justify-center items-center border border-secondary-50 text-transparent size-4;
        background: transparent no-repeat center;
    }


    .box-input-wrapper{
        @apply flex font-roboto;
    }

    .box-input-wrapper .box-input {
        @apply block border-secondary-50 p-2 border text-center text-gray-900 text-xs size-7 bg-transparent;
    }

    .box-input-wrapper .box-input:not(:first-child) {
        @apply border-y border-r border-l-0;
    }

    .number-input {
        appearance: textfield;

        &::-webkit-inner-spin-button,
        &::-webkit-outer-spin-button {
            appearance: none;
        }
    }
</style>
