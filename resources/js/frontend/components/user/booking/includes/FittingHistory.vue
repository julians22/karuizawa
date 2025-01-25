<script setup>
    import { ref, defineAsyncComponent, onMounted } from 'vue';
    import { TailwindPagination } from 'laravel-vue-pagination';
    import moment from 'moment';

    const props = defineProps({
        api_fitting_url: String,
        filterData: {
            type: Object,
            default: () => ({
                date: '',
                status: '',
                applyKeyword: ''
            })
        },
        user: Object,
        api_set_handling: String
    });

    const isRetrieving = ref(false);

    const DetailOrder = defineAsyncComponent(() => import('../utils/DetailFitting.vue'));
    const SetHandlingDate = defineAsyncComponent(() => import('../utils/HandlingDate.vue'));

    const orderDetail = ref(false);
    const handlingDate = ref(false);


    const bookings = ref(null);

    onMounted(() => {
        getBookings();
    });

    const getResults = async (page = 1) => {
        getBookings(page);
    }

    const getBookings = async (page) => {
        isRetrieving.value = true;

        let url = props.api_fitting_url + '?page=' + page;
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

    const onClickFittingDate = async (booking) => {
        handlingDate.value.dialog = true
        handlingDate.value.order_item = await booking
    }

    const handlingDateUpdated = (booking_item) => {
        getBookings();
    } 

    defineExpose({
        getBookings
    })
</script>

<template>
    <div>
        <DetailOrder ref="orderDetail"/>
        <SetHandlingDate ref="handlingDate" :api_set_handling="api_set_handling" @handling-date-updated="handlingDateUpdated"/>

        <div class="pt-10 pb-28 container">
            <div v-if="isRetrieving">Loading Orders ...</div>
            <div v-else-if="!isRetrieving && bookings" v-for="booking in bookings.data">
                <div class="flex justify-between">
                    <div class="space-y-2 font-roboto">
                        <div class="font-bold text-xl">Booking Number: {{ booking.order.order_number}}</div>
                        <div>
                            {{ booking.product.customer.full_name }}
                        </div>
                        <div>{{booking.product.customer.address ?? 'N/A'}}</div>
                        <div>{{ booking.product.customer.phone }}</div>
                        <div>{{ booking.product.customer.is_male ? 'Male' : 'Female' }}</div>
                    </div>
                    <div class="space-y-3 font-roboto">
                        <div class="font-bold text-xl">Handling Date</div>
                        <div v-if="booking.product.handling_date">{{ moment(booking.product.handling_date).format("YYYY-MM-DD") }}</div>
                        <div v-else>
                            <button @click="onClickFittingDate(booking)" 
                                class="flex items-center gap-3 bg-primary-50 px-4 lg:px-3 py-2 lg:py-2 font-josefin text-white tracking-widest">
                                <span class="mt-1 text-xs">SET HANDLING DATE</span>
                                <img class="inline-block size-4" src="img/icons/arrw-ck-right.png" alt="">
                            </button>
                        </div>
                        <div class="font-bold text-xl">Booking Time</div>
                        <div>{{ moment(booking.order.order_date).format("YYYY-MM-DD | H:m") }}</div>
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

        <!-- <div class="right-0 bottom-0 absolute flex">
            <button class="flex items-center gap-2 bg-primary-50 p-6 text-white tracking-widest">
                <span>NEXT PAGE</span>
                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
            </button>
            <button class="flex items-center gap-2 bg-secondary-50 p-6 text-white tracking-widest">
                <span>PAGE 1 of 100</span>
                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
            </button>
        </div> -->
    </div>
</template>
