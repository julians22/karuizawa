<script setup>
    import { ref, defineAsyncComponent, onMounted } from 'vue';

    const props = defineProps({
        api_booking_url: String,
    });

    const isRetrieving = ref(false);

    const DetailOrder = defineAsyncComponent(() => import('../utils/DetailHistoryOrder.vue'));

    const orderDetail = ref(false);

    const bookings = ref(null);

    onMounted(() => {
        getBookings();
    });

    const getBookings = async () => {
        isRetrieving.value = true;
        const response = await fetch(props.api_booking_url);
        bookings.value = await response.json();
        isRetrieving.value = false;
    }

    const onClickDetail = async (booking) => {
        console.log(booking);
        orderDetail.value.dialog = true
        orderDetail.value.booking = await booking
    }
</script>

<template>
    <div>
        <DetailOrder ref="orderDetail"/>
        <div class="pt-10 pb-28 container">
            <div v-if="isRetrieving">Loading Orders ...</div>
            <div v-else-if="!isRetrieving && bookings" v-for="booking in bookings">
                <div class="flex justify-between">
                    <div class="space-y-2 font-roboto">
                        <div class="font-bold text-xl">Booking Number: {{ booking.booking_code }}</div>
                        <div>
                            {{ booking.customer_name }}
                        </div>
                        <div>{{booking.customer_address}}</div>
                        <div>{{ booking.customer_phone }}</div>
                        <div>{{ booking.customer_gender }}</div>
                    </div>
                    <div class="space-y-3 font-roboto">
                        <div class="font-bold text-xl">Booking Time</div>
                        <div>{{ booking.order_date }}</div>
                        <button @click="onClickDetail(booking)" class="flex items-center gap-3 bg-primary-50 px-4 lg:px-3 py-2 lg:py-2 font-josefin text-white tracking-widest">
                            <span class="mt-1 text-xs">SEE DETAIL</span>
                            <img class="inline-block size-4" src="img/icons/arrw-ck-right.png" alt="">
                        </button>
                    </div>
                </div>
                <div class="bg-black my-6 w-full h-0.5"></div>
            </div>
        </div>
    </div>
</template>
