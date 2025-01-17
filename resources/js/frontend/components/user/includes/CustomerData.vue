<script setup>
    import axios from 'axios';
    import { defineEmits, ref, defineAsyncComponent, onMounted, watch, computed, reactive, defineProps } from 'vue';
    import { useCustomer } from '../../../store/customer';

    const props = defineProps({
        onPage: {
            type: String,
            default: 'rtw'
        }
    })

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
            <div class="flex items-center justify-between p-6 lg:py-7 lg:px-14 bg-primary-50">
                <div class="text-lg font-bold tracking-widest text-white uppercase lg:text-xl">Customer data</div>
                <button @click="childAddCustomer.open = true" class="tracking-widest text-white uppercase max-lg:text-sm">Add Customer <span class="text-2xl font-roboto">+</span></button>
            </div>

            <div class="container py-10">
                <div class="grid items-center grid-cols-6 mb-4">
                    <label for="first_name" class="block col-span-2 mb-2 font-medium tracking-widest uppercase lg:col-span-1 text-primary-50 ">First name</label>
                    <input type="text" v-model="form.first_name" id="first_name" class="col-span-4 lg:col-span-5 bg-transparent border border-primary-50 text-primary-50 rounded-full block w-full p-2.5"/>
                </div>
                <div class="grid items-center grid-cols-6 mb-4">
                    <label for="phone" class="block col-span-2 mb-2 font-medium tracking-widest uppercase lg:col-span-1 text-primary-50 ">Phone No</label>
                    <input type="text" v-model="form.phone" id="phone" class="col-span-4 lg:col-span-5 bg-transparent border border-primary-50 text-primary-50 rounded-full block w-full p-2.5"/>
                </div>
                <div class="grid items-center grid-cols-6 mb-4">
                    <label for="email" class="block col-span-2 mb-2 font-medium tracking-widest uppercase lg:col-span-1 text-primary-50">email</label>
                    <input type="email" id="email" v-model="form.email" class="col-span-4 lg:col-span-5 bg-transparent border border-primary-50 text-primary-50 rounded-full block w-full p-2.5"/>
                </div>
                <div class="grid items-center grid-cols-6">
                    <label class="block col-span-2 mb-2 font-medium tracking-widest uppercase lg:col-span-1 text-primary-50">gender</label>
                    <div class="flex col-span-4 gap-4 lg:col-span-5">
                        <div class="flex items-center">
                            <input :checked="form.is_male && form.is_male !== null" v-model="form.is_male" :value="true" id="default-radio-1" type="radio" name="default-radio" class="w-4 h-4 border-gray-300 text-secondary-50 bg-secondary focus:ring-secondary">
                            <label for="default-radio-1" class="mt-1 uppercase text-primary-50 ms-1">Male</label>
                        </div>
                        <div class="flex items-center">
                            <input :checked="!form.is_male && form.is_male !== null" id="default-radio-2" type="radio" v-model="form.is_male" :value="false" name="default-radio" class="w-4 h-4 border-gray-300 text-secondary-50 bg-secondary focus:ring-secondary">
                            <label for="default-radio-2" class="mt-1 uppercase text-primary-50 ms-1">Female</label>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="pb-40">
            <div class="flex items-center justify-between p-6 lg:py-7 lg:px-14 bg-primary-50">
                <div class="text-lg font-bold tracking-widest text-white uppercase lg:text-xl">CONFIRM DATE & TIME</div>
            </div>
            <div class="container flex gap-10 py-10">
                <div class="flex items-center gap-6">
                    <label for="date" class="block mt-1 mb-2 uppercase text-primary-50">set date</label>
                    <div class="flex">
                        <input type="date" id="date" class="rounded-full bg-transparent border text-primary-50 leading-none block flex-1 w-full  border-primary-50 p-2.5 relative before:-z-10 before:content-['']  before:absolute before:right-0 before:w-10 before:bg-primary-50 before:block before:inset-y-0" required >
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <label for="time" class="block mt-1 mb-2 uppercase text-primary-50">set time</label>
                    <div class="flex">
                        <input type="time" id="time" class="rounded-full bg-transparent border text-primary-50 leading-none block flex-1 w-full  border-primary-50 p-2.5 relative before:-z-10 before:content-['']  before:absolute before:right-0 before:w-10 before:bg-primary-50 before:block before:inset-y-0" min="00:00" max="23:00" value="00:00" required>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 flex">
                <button v-if="props.onPage == 'rtw'" @click="btnSkip()" class="flex items-center gap-2 p-6 tracking-widest text-white uppercase bg-primary-50">
                    <span>skip</span>
                    <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
                </button>
                <button @click="submit()" class="flex items-center gap-2 p-6 tracking-widest text-white uppercase bg-secondary-50">
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
