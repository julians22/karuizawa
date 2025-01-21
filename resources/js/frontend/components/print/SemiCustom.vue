<script setup>
    import { defineAsyncComponent, ref, watch, computed, onMounted } from 'vue';

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
</script>

<template>
    <div class="printable w-[1400px]">
        <CustomBasic @additionalBasic="additionalBasic" :dataConfig="props.data_config" :dataSemiCustom="props.data_semi_custom" ref="childBasic" />
        <CustomOptions @additionalOption="additionalOption" :dataConfig="props.data_config" :dataSemiCustom="props.data_semi_custom" ref="childOption"/>
    </div>
</template>
