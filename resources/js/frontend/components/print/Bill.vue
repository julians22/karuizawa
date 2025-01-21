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

const componentRef = ref();
const { handlePrint } = useVueToPrint({
  content: componentRef,
  documentTitle: "AwesomeFileName",
  removeAfterPrint: true
});
</script>

<template>
    <div class="max-w-md p-4 mx-auto">
        <button @click="handlePrint" class="inline-block p-2 mx-auto text-white bg-primary-50">Print</button>
        <div ref="componentRef" class="max-w-md border">
          <div>
              <div class="text-lg font-bold text-center">KARUIZAWA SHIRT</div>
              <div class="text-sm font-bold text-center">{{ props.data_order.store.address }}</div>
              <div class="text-center">Karuizawashirt.official</div>
              <div class="mt-2 text-center">Casier: {{ props.data_order.user.name }}</div>
              <div class="text-center">{{ moment(props.data_order.payments[0].created_at).format("DD MMM YYYY  hh:mm:ss") }}</div>
              <div class="w-full h-px bg-black"></div>
              <table class="w-full px-2">

                <tbody>
                    <template v-for="(order, index) in props.data_order.order_items">
                        <tr>
                            <td colspan="3">{{ productName(order) }}</td>
                        </tr>
                        <tr>
                            <td width="20%">{{ order.quantity + 'x' }}</td>
                            <td width="20%">{{ priceFormat2(order.total_price) }}</td>
                            <td class="text-right">{{ priceFormat(order.price) }}</td>
                        </tr>
                    </template>

                    <tr class="pt-4 border-t">
                        <td colspan="2" class="text-right">Subtotal:</td>
                        <td class="text-right">{{ priceFormat(subTotal) }}</td>
                    </tr>

                    <tr>
                        <td colspan="2" class="text-right">Disc ({{ props.data_order.discount_details.coupon }} %):</td>
                        <td class="text-right">{{ priceFormat(props.data_order.discount) }}</td>
                    </tr>

                    <tr>
                        <td colspan="2" class="text-right">Total:</td>
                        <td class="text-right">{{ priceFormat(props.data_order.total_price) }}</td>
                    </tr>
                </tbody>
              </table>

              <div class="w-full h-px bg-black"></div>

              Terms & Conditions:

                <div class="mb-2 text-xs">
                    <strong class="mb-1">Semi-Respoke</strong>
                    <ol>
                        <li>The production process will require 14 working days, excluding holidays and weekends.</li>
                        <li>Karuizawa will charge a revision fee for any changes or modifications depending on the nature and complexity.</li>
                        <li>No refund policy.</li>
                    </ol>
                </div>

                <div class="text-xs">
                    <strong class="mb-1">Ready to Wear</strong>
                    <ol>
                        <li>Karuizawa allows any product exchange only for the cause of production's mishaps.</li>
                        <li>Karuizawa needs to receive the receipt, product with tag and label intact during the exchange process within 3 days after purchase.</li>
                    </ol>
                </div>

                <!-- border dot -->
                <div class="w-full h-px border-t border-black border-dotted"></div>
          </div>
        </div>
    </div>
</template>
