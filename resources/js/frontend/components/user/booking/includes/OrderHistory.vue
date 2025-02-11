<script setup>
    import { ref, defineAsyncComponent, onMounted } from 'vue';
    import { TailwindPagination } from 'laravel-vue-pagination';


    const props = defineProps({
        api_booking_url: String,
        filterData: {
            type: Object,
            default: () => ({
                date: '',
                status: '',
                applyKeyword: ''
            })
        },
        user: Object
    });

    const isRetrieving = ref(false);

    const DetailOrder = defineAsyncComponent(() => import('../utils/DetailHistoryOrder.vue'));

    const orderDetail = ref(false);
    const bookings = ref(null);


    onMounted(() => {
        getBookings();
    });

    const getResults = async (page = 1) => {
        getBookings(page);
    }

    const getBookings = async (page) => {
        isRetrieving.value = true;

        let url = props.api_booking_url + '?page=' + page;
        if (props.filterData.date && props.filterData.date !== '') {
            url += `&date=${props.filterData.date}`;
        }

        if (props.filterData.status && props.filterData.status !== '') {
            url += `&status=${props.filterData.status}`;
        }

        if (props.filterData.keyword && props.filterData.keyword !== '') {
            url += `&keyword=${props.filterData.keyword}`;
        }

        if (props.user && props.user.store_id) {
            url += `&store_id=${props.user.store_id}`;
        }

        const response = await fetch(url);
        bookings.value = await response.json();
        isRetrieving.value = false;
    }

    const onClickDetail = async (booking) => {
        orderDetail.value.dialog = true
        orderDetail.value.booking = await booking
    }

    defineExpose({
        getBookings
    })
</script>

<template>
    <div>
        <DetailOrder ref="orderDetail"/>
        <div class="pt-10 pb-28 container">
            <div v-if="isRetrieving">Loading Orders ...</div>
            <div v-else-if="!isRetrieving && bookings" v-for="booking in bookings.data">
                <div class="gap-2 grid grid-cols-10">
                    <div class="space-y-2 col-span-7 font-roboto">
                        <div class="font-bold text-xl">Booking Number: {{ booking.booking_code }}</div>
                        <div>
                            {{ booking.customer_name }}
                        </div>
                        <div>{{booking.customer_address}}</div>
                        <div>{{ booking.customer_phone }}</div>
                        <div>{{ booking.customer_gender }}</div>
                    </div>
                    <div class="justify-self-end space-y-3 col-span-3 font-roboto">
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

        <div class="flex justify-center pb-20">
            <TailwindPagination
                v-if="bookings"
                :data="bookings"
                :limit="2"
                :active-classes="['bg-primary-50', 'text-white']"
                @pagination-change-page="getResults"
            />
        </div>
    </div>
</template>
