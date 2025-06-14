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
        api_set_handling: String,
        api_set_status: String
    });

    const isRetrieving = ref(false);

    const DetailOrder = defineAsyncComponent(() => import('../utils/DetailFitting.vue'));
    const SetHandlingDate = defineAsyncComponent(() => import('../utils/HandlingDate.vue'));
    const SetStatusDialog = defineAsyncComponent(() => import('../utils/StatusDialog.vue'));

    const orderDetail = ref(false);
    const handlingDate = ref(false);
    const statusDialog = ref(null);


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

    const onClickConfirmStatus = async (booking) => {
        statusDialog.value.dialog = true
        statusDialog.value.product = await booking.product
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
        <SetStatusDialog ref="statusDialog" :api_set_status="props.api_set_status" @get-bookings="getBookings"/>

        <div class="pt-10 pb-28 container">
            <div v-if="isRetrieving">Loading Orders ...</div>
            <div v-else-if="!isRetrieving && bookings" v-for="booking in bookings.data">
                <div class="gap-2 grid grid-cols-10">
                    <div class="space-y-2 col-span-7 font-roboto">
                        <div class="flex items-center gap-2 font-bold text-xl">
                            <span>Booking Number: {{ booking.product.order_number}}</span>
                            <span v-if="booking.product.status === 'processing'" class="bg-yellow-100 dark:bg-gray-700 me-2 px-2.5 py-0.5 border border-yellow-300 rounded-sm font-medium text-yellow-800 dark:text-yellow-300 text-xs">Processing</span>
                            <span v-else-if="booking.product.status === 'finish'" class="bg-green-100 dark:bg-gray-700 me-2 px-2.5 py-0.5 border border-green-400 rounded-sm font-medium text-green-800 dark:text-green-400 text-xs">Order Completed</span>
                        </div>
                        <div>
                            {{ booking.product.customer.full_name }}
                        </div>
                        <div>{{booking.product.customer.address ?? 'N/A'}}</div>
                        <div>{{ booking.product.customer.phone }}</div>
                        <div>{{ booking.product.customer.is_male ? 'Male' : 'Female' }}</div>
                    </div>
                    <div class="justify-self-end space-y-3 col-span-3 font-roboto">
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
                        <div class="font-bold text-xl">Status</div>
                        <div v-if="booking.product.finish_at">{{ moment(booking.product.finish_at).format("YYYY-MM-DD | H:m") }}</div>
                        <div v-else>
                            <button @click="onClickConfirmStatus(booking)"
                                class="flex items-center gap-3 bg-primary-50 px-4 lg:px-3 py-2 lg:py-2 font-josefin text-white tracking-widest">
                                <span class="mt-1 text-xs">COMPLETE ORDER</span>
                                <img class="inline-block size-4" src="img/icons/arrw-ck-right.png" alt="">
                            </button>
                        </div>
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
