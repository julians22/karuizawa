<script setup>
    import { defineAsyncComponent, ref, watch, computed, onMounted } from 'vue';

    const props = defineProps({
        data_semi_custom: Object,
    });
</script>

<template>
    <div>
        <!-- <template v-if="currentSection == 'custom-shirt'"> -->
        <CustomShirt @additionalBasic="additionalBasic" :dataSemiCustom="props.data_semi_custom" ref="childBasic" />
        <!-- </template> -->

        <!-- <template v-if="currentSection == 'custom-request'"> -->
        <CustomRequest @additionalOption="additionalOption" :dataOptions="props.data_semi_custom" ref="childOption"/>
        <!-- </template> -->
    </div>
</template>
