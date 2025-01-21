<script setup>
import { useVueToPrint } from "vue-to-print";
import { priceFormat, priceFormat2 } from "@frontend/helpers/currency";
import moment from "moment";
import { ref } from "vue";

const props = defineProps({
    data_order: Object,
});

const componentRef = ref();
const { handlePrint } = useVueToPrint({
  content: componentRef,
  documentTitle: "AwesomeFileName",
});
</script>

<template>
  <button @click="handlePrint">Print</button>
  <div ref="componentRef" class="max-w-md border ">
    <div>
        <div class="text-lg font-bold text-center">KARUIZAWA SHIRT</div>
        <div class="text-lg font-bold text-center">{{ props.data_order.store.address }}</div>
        <div class="text-center">Karuizawashirt.official</div>
        <div class="mt-4 text-center">Casier: {{ props.data_order.user.name }}</div>
        <div class="text-center">{{ moment(props.data_order.payments.created_at).format("DD MMM YYYY  H:mm:ss") }}</div>
        <div class="w-full h-0.5 bg-black"></div>
        <div v-for="order in props.data_order.order_items">
            <div>{{ order.product.name }}</div>
            <div class="grid grid-cols-3">
                <div>{{ order.quantity + 'x' }}</div>
                <div>{{ priceFormat2(order.total_price) }}</div>
                <div class="text-right">{{ priceFormat(order.price) }}</div>
            </div>
        </div>
        <div class="w-full h-0.5 bg-black"></div>
    </div>
  </div>
</template>
