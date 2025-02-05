<script setup>
import { useVueToPrint } from "vue-to-print";
import { priceFormat, priceFormat2, percentPrice } from "@frontend/helpers/currency";
import moment from "moment";
import { computed, ref } from "vue";

const props = defineProps({
    data_order: Object,
});

const productName = (item) => {
    if (item.type === 'RTW') {
        return item.product.product_name;
    } else {
        return item.product.name;
    }
};

const subTotal = computed(() => {
    // convert to number from float
    let total = parseFloat(props.data_order.total_price) + parseFloat(props.data_order.discount);

    return total;
});

const orderDate = computed(() => {
    let orderPaymentDate = props.data_order.payments[0]?.created_at;

    if (orderPaymentDate === null) {
        orderPaymentDate = props.data_order.order_date;
    }
    return moment(orderPaymentDate).format("DD MMM YYYY hh:mm:ss");
});

const componentRef = ref();
const { handlePrint } = useVueToPrint({
  content: componentRef,
  documentTitle: "AwesomeFileName",
  removeAfterPrint: true
});
</script>

<template>
    <div class="mx-auto max-w-[80mm]">
        <div ref="componentRef" class="px-2 border w-full font-sans print:font-sans" style="font-size: 10px;">
          <div>
              <div class="font-semibold text-center">KARUIZAWA SHIRT</div>
              <div class="font-semibold text-center leading-snug">{{ props.data_order.store.address }}</div>
              <div class="text-center">@karuizawashirt.official</div>
              <div class="text-center">Casier: {{ props.data_order.user.name }}</div>
              <div class="text-center">{{ orderDate }}</div>
              <div class="bg-black w-full h-px"></div>
              <table class="w-full">

                <tbody>
                    <template v-for="(order, index) in props.data_order.order_items">
                        <tr>
                            <td colspan="3">{{ productName(order) }}</td>
                        </tr>
                        <tr>
                            <td width="20%">{{ order.quantity + 'x' }}</td>
                            <td width="20%">{{ priceFormat2(order.price) }}</td>
                            <td class="text-right"><span v-if="order.discount_detail?.discount">(-{{ order.discount_detail.discount }}%)</span>{{ priceFormat(order.total_price - (order?.discount ?? 0)) }}  </td>
                        </tr>
                    </template>

                    <tr class="pt-4 border-t">
                        <td colspan="2" class="text-right">Subtotal:</td>
                        <td class="text-right">{{ priceFormat(subTotal) }}</td>
                    </tr>

                    <tr v-if="props.data_order.discount > 0">
                        <td colspan="2" class="text-right">Disc ({{ props.data_order.discount_details.coupon }} %):</td>
                        <td class="text-right">{{ priceFormat(props.data_order.discount) }}</td>
                    </tr>

                    <tr>
                        <td colspan="2" class="text-right">Total:</td>
                        <td class="text-right">{{ priceFormat(props.data_order.total_price) }}</td>
                    </tr>
                </tbody>
              </table>

              <div class="bg-black w-full h-px"></div>

                <div class="py-2">
                    <strong>Terms & Conditions:</strong>
                    <div class="mb-2">
                        <strong class="mb-1">Semi-Respoke</strong>
                        <ol class="list-decimal list-inside">
                            <li>The production process will require 14 working days, excluding holidays and weekends.</li>
                            <li>Karuizawa will charge a revision fee for any changes or modifications depending on the nature and complexity.</li>
                            <li>No refund policy.</li>
                        </ol>
                    </div>
                    <div class="">
                        <strong class="mb-1">Ready to Wear</strong>
                        <ol class="list-decimal list-inside">
                            <li>Karuizawa allows any product exchange only for the cause of production's mishaps.</li>
                            <li>Karuizawa needs to receive the receipt, product with tag and label intact during the exchange process within 3 days after purchase.</li>
                        </ol>
                    </div>
                </div>

                <!-- border dot -->
                <div class="border-t border-black border-dotted w-full h-px"></div>
          </div>
        </div>

        <button @click="handlePrint" class="block print:hidden bg-primary-50 mx-auto mb-2 p-2 text-white">Print</button>
    </div>
</template>
