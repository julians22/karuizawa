<script setup>
    import axios from 'axios';
    import { defineEmits, ref, defineAsyncComponent, onMounted, watch, computed, reactive, defineProps } from 'vue';
    import { useCustomer } from '../../../store/customer';
    import { useOrder } from '../../../store/order';
    import { useProducts } from '../../../store/product';
    import VueDatePicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css';

    const props = defineProps({
        onPage: {
            type: String,
            default: 'rtw'
        }
    });

    const date = ref(new Date());

    // In case of a range picker, you'll receive [Date, Date]
    const format = (date) => {
        const day = date.getDate();
        const month = date.getMonth() + 1;
        const year = date.getFullYear();

        return `${day}/${month}/${year}`;
    }

    const time = ref({
        hours: new Date().getHours(),
        minutes: new Date().getMinutes()
    });


    onMounted(() => {
        useOrder().orderDate = format(date.value);
        useOrder().orderTime = `${time.value.hours}:${time.value.minutes}`;
    });

    const addCustumer = defineAsyncComponent(() => import('../../utils/modalSelectCustomer.vue'));

    const childAddCustomer = ref(null);

    const $emit = defineEmits(['btn-next']);

    const store = useCustomer();

    const customer = computed(() => {
        if (childAddCustomer.value?.customer) {
            return childAddCustomer.value?.customer
        }
    });

    const selectedCustomer = ref(null);

    const form = ref({
        id: null,
        first_name:'',
        phone: '',
        email: '',
        is_male: null,
    });

    watch(customer, (items) => {
        form.value.id = items.id;
        form.value.first_name = items.full_name;
        form.value.phone = items.phone;
        form.value.email = items.email;
        form.value.is_male = items.is_male;
    });

    watch(date, (items) => {
        useOrder().orderDate = format(items);
    });

    watch(time, (items) => {
        useOrder().orderTime = `${items.hours}:${items.minutes}`;
    });

    const submit = () => {
        axios.post('/api/customer/store', form.value)
            .then(response => {
                if (response.data.success) {
                    selectedCustomer.value = response.data.data
                    store.customer = response.data.data;
                    onSubmit()
                }
            })
            .catch(error => {
                console.error('There was an error!', error.response.data.message);
                alert(error.response.data.message);
            });
    }

    const onSubmit = () => {
        if (props.onPage == 'rtw') {
            $emit('btn-next', 'products');
        }else if (props.onPage == 'semi-custom') {
            $emit('btn-next', 'semi-custom');
        }
    }

    const btnSkip = () => {
        $emit('btn-next', 'products');
    }
</script>

<template>
    <div>
        <addCustumer ref="childAddCustomer" />
        <section>
            <div class="flex justify-between items-center bg-primary-50 lg:px-14 lg:py-7 p-6">
                <div class="font-bold text-lg text-white lg:text-xl uppercase tracking-widest">Customer data</div>
                <button @click="childAddCustomer.open = true" class="text-white max-lg:text-sm uppercase tracking-widest">Add Customer <span class="font-roboto text-2xl">+</span></button>
            </div>

            <div class="py-10 container">
                <div class="items-center grid grid-cols-6 mb-4">
                    <label for="first_name" class="block col-span-2 lg:col-span-1 mb-2 font-medium text-primary-50 uppercase tracking-widest">First name</label>
                    <input type="text" v-model="form.first_name" id="first_name" class="block border-primary-50 col-span-4 lg:col-span-5 bg-transparent p-2.5 border rounded-full w-full text-primary-50"/>
                </div>
                <div class="items-center grid grid-cols-6 mb-4">
                    <label for="phone" class="block col-span-2 lg:col-span-1 mb-2 font-medium text-primary-50 uppercase tracking-widest">Phone No</label>
                    <input type="text" v-model="form.phone" id="phone" class="block border-primary-50 col-span-4 lg:col-span-5 bg-transparent p-2.5 border rounded-full w-full text-primary-50"/>
                </div>
                <div class="items-center grid grid-cols-6 mb-4">
                    <label for="email" class="block col-span-2 lg:col-span-1 mb-2 font-medium text-primary-50 uppercase tracking-widest">email</label>
                    <input type="email" id="email" v-model="form.email" class="block border-primary-50 col-span-4 lg:col-span-5 bg-transparent p-2.5 border rounded-full w-full text-primary-50"/>
                </div>
                <div class="items-center grid grid-cols-6">
                    <label class="block col-span-2 lg:col-span-1 mb-2 font-medium text-primary-50 uppercase tracking-widest">gender</label>
                    <div class="flex gap-4 col-span-4 lg:col-span-5">
                        <div class="flex items-center">
                            <input :checked="form.is_male && form.is_male !== null" v-model="form.is_male" :value="true" id="default-radio-1" type="radio" name="default-radio" class="border-gray-300 bg-secondary focus:ring-secondary w-4 h-4 text-secondary-50">
                            <label for="default-radio-1" class="mt-1 text-primary-50 uppercase ms-1">Male</label>
                        </div>
                        <div class="flex items-center">
                            <input :checked="!form.is_male && form.is_male !== null" id="default-radio-2" type="radio" v-model="form.is_male" :value="false" name="default-radio" class="border-gray-300 bg-secondary focus:ring-secondary w-4 h-4 text-secondary-50">
                            <label for="default-radio-2" class="mt-1 text-primary-50 uppercase ms-1">Female</label>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="pb-40">
            <div class="flex justify-between items-center bg-primary-50 lg:px-14 lg:py-7 p-6">
                <div class="font-bold text-lg text-white lg:text-xl uppercase tracking-widest">CONFIRM DATE & TIME</div>
            </div>
            <div class="flex gap-10 py-10 container">
                <div class="flex items-center gap-6">
                    <label for="date" class="block text-primary-50 uppercase">set date</label>
                    <div class="flex">
                        <VueDatePicker v-model="date" :format="format" :min-date="new Date()" ignore-time-validation :enableTimePicker="false" />
                        <!-- <input type="date" id="date" class="block before:block relative before:right-0 before:-z-10 before:absolute before:inset-y-0 flex-1 before:content-[''] border-primary-50 bg-transparent before:bg-primary-50 p-2.5 border rounded-full w-full before:w-10 text-primary-50 leading-none" required > -->
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <label for="time" class="block text-primary-50 uppercase">set time</label>
                    <div class="flex">
                        <VueDatePicker v-model="time" :min-time="{ hours: 10 }" :max-time="{ hours: 21, minutes: 10 }" time-picker>
                            <template #input-icon>
                                <img class="ml-2 input-slot-image size-5" src="/img/icons/time.png"/>
                            </template>
                        </VueDatePicker>
                    </div>
                </div>
            </div>
            <div class="right-0 bottom-0 absolute flex">
                <button v-if="props.onPage == 'rtw'" @click="btnSkip()" class="flex items-center gap-2 bg-primary-50 p-6 text-white uppercase tracking-widest">
                    <span>skip</span>
                    <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
                </button>
                <button @click="submit()" class="flex items-center gap-2 bg-secondary-50 p-6 text-white uppercase tracking-widest">
                    <span>submit</span>
                    <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
                </button>
            </div>
        </section>
    </div>
</template>


<style>
    input[type="date"]::-webkit-calendar-picker-indicator {
        background-image: url("/img/icons/calendar.png");
        z-index: 1;
    }
    input[type="time"]::-webkit-calendar-picker-indicator {
        background-image: url("/img/icons/time.png");
        margin-left: 2rem;
        z-index: 1;
    }
</style>
