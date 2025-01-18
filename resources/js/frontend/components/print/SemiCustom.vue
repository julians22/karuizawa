<script setup>
    import { defineAsyncComponent, ref, watch, computed, onMounted } from 'vue';

    const props = defineProps({
        data_semi_custom: Object,

    });

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
    <div>

        tset
        <!-- <CustomShirt @additionalBasic="additionalBasic" :dataSemiCustom="props.data_semi_custom" ref="childBasic" />
        <CustomRequest @additionalOption="additionalOption" :dataOptions="props.data_semi_custom" ref="childOption"/> -->
    </div>
</template>
