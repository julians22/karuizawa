<script setup>
import { useVueToPrint } from "vue-to-print";
import { priceFormat, priceFormat2, percentPrice } from "@frontend/helpers/currency";
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
    <div>
        <div class="flex justify-center">
            <button @click="handlePrint" class="px-3 py-2 mx-auto mb-4 text-white uppercase rounded-md bg-primary-50 print:hidden">Print</button>
        </div>
        <div class="flex justify-center">
            <div ref="componentRef" class="max-w-md p-4 border print:border-[0]">
              <div>
                  <div class="text-lg font-bold text-center">KARUIZAWA SHIRT</div>
                  <div class="text-lg font-bold text-center">{{ props.data_order.store.address }}</div>
                  <div class="text-center">Karuizawashirt.official</div>
                  <div class="mt-4 text-center">Casier: {{ props.data_order.user.name }}</div>
                  <div class="text-center">{{ moment(props.data_order.payments.created_at).format("DD MMM YYYY  H:mm:ss") }}</div>
                  <div class="w-full h-0.5 bg-black"></div>
                  <div v-for="order in props.data_order.order_items">
                      <div>{{ order.product.product_name ?? order.product.name }}</div>
                      <div class="grid grid-cols-3">
                          <div>{{ order.quantity + 'x' }}</div>
                          <div>{{ priceFormat2(order.price) }}</div>
                          <div class="text-right">{{ priceFormat(order.total_price) }}</div>
                      </div>
                  </div>
                  <div class="w-full h-0.5 bg-black"></div>
                  <table class="ml-[9rem]">
                      <thead>
                          <tr>
                              <td class="text-right whitespace-nowrap">Sub Total</td>
                              <td>:</td>
                              <td class="w-full text-right">{{ priceFormat(props.data_order.payments[0].amount) }}</td>
                          </tr>
                          <tr>
                              <td class="text-right whitespace-nowrap">Disc ({{ props.data_order.discount }})</td>
                              <td>:</td>
                              <td class="w-full text-right">{{ percentPrice(props.data_order.discount, props.data_order.payments[0].amount) }}</td>
                          </tr>
                          <tr>
                              <td class="text-right whitespace-nowrap">Total</td>
                              <td>:</td>
                              <td class="w-full text-right">{{ priceFormat(props.data_order.payments[0].amount) }}</td>
                          </tr>
                          <tr>
                              <td class="text-right whitespace-nowrap">Payment</td>
                              <td>:</td>
                              <td class="w-full text-right">{{ props.data_order.payments[0].payment + ' ' + props.data_order.payments[0].bank}}</td>
                          </tr>
                      </thead>
                  </table>
                  <div class="mt-2 text-left">
                    <div>Trems and Conditions:</div>
                    <div class="text-lg font-bold">Semi-Respoke</div>
                    <div>1. The production process will require 14 working days, exclude holidays and weekends.</div>
                    <div>2. Karuizawa will charge a revision fee for any charges or modifications depending on the nature and comolexity.</div>

                    <div class="mt-4 text-lg font-bold">Ready-To-Wear</div>
                    <div>1. Karuizawa allows any product exchange only for the cause of production's mishaps</div>
                    <div>2. Karuizawa needs to receive the receive, product with tag and label intact during the exchange process within 3 days after purchase</div>
                  </div>

                  <div class="border-t border-black border-dashed"></div>
                  <div>{{ props.data_order.store.name }}</div>
              </div>
            </div>
        </div>
    </div>
</template>
