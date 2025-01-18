<script setup>

    import { ref, onMounted } from 'vue';

    const props = defineProps({
        api_incoming_url: String,
        filterData: {
            type: Object,
            default: () => ({
                date: '',
                status: '',
                applyKeyword: ''
            })
        }
    });

    const isRetrieving = ref(false);

    const bookings = ref(null);

    onMounted(() => {
        getBookings();
    });

    const getBookings = async () => {
        isRetrieving.value = true;

        let url = props.api_incoming_url + '?page=1';
        if (props.filterData.date && props.filterData.date !== '') {
            url += `&date=${props.filterData.date}`;
        }
        if (props.filterData.status && props.filterData.status !== '') {
            url += `&status=${props.filterData.status}`;
        }

        if (props.filterData.keyword && props.filterData.keyword !== '') {
            url += `&keyword=${props.filterData.keyword}`;
        }

        const response = await fetch(url);
        bookings.value = await response.json();
        isRetrieving.value = false;
    }

    defineExpose({
        getBookings
    })

</script>

<template>
    <div>
        <div class="pt-10 pb-28 container">
            <div v-if="isRetrieving">Loading Orders ...</div>
            <div v-for="booking in bookings" v-else-if="!isRetrieving && bookings">
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
                        <button class="flex items-center gap-3 bg-primary-50 px-4 lg:px-3 py-2 lg:py-2 font-josefin text-white tracking-widest">
                            <span class="mt-1 text-xs">PAY NOW</span>
                            <img class="inline-block size-4" src="img/icons/arrw-ck-right.png" alt="">
                        </button>
                        <button class="flex items-center gap-3 bg-secondary-50 px-4 lg:px-3 py-2 lg:py-2 font-josefin text-white tracking-widest">
                            <span class="mt-1 text-xs">CANCEL ORDER</span>
                            <img class="inline-block size-4" src="img/icons/arrw-ck-right.png" alt="">
                        </button>
                    </div>
                </div>
                <div class="bg-black my-6 w-full h-0.5"></div>
            </div>
            <!-- empty result -->
            <div v-if="!isRetrieving && bookings && bookings.length === 0">
                <div class="font-bold text-2xl text-center">No Incoming Order yet</div>
            </div>
        </div>
    </div>
</template>
