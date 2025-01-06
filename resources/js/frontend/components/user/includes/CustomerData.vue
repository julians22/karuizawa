<script setup>
    import { defineEmits } from 'vue';

    const $emit = defineEmits(['btn-next']);

    const btnSkip = () => {
        $emit('btn-next', 'rtw')
    }
</script>

<template>
    <div>
        <section>
            <div class="flex items-center justify-between p-6 lg:py-7 lg:px-14 bg-primary-50">
                <div class="text-lg font-bold tracking-widest text-white uppercase lg:text-xl">Customer data</div>
                <div class="tracking-widest text-white uppercase max-lg:text-sm">Add Customer <span class="text-2xl font-roboto">+</span></div>
            </div>

            <div class="container py-10">
                <div class="grid items-center grid-cols-6 mb-4">
                    <label for="first_name" class="block col-span-2 mb-2 font-medium tracking-widest uppercase lg:col-span-1 text-primary-50 ">First name</label>
                    <input type="text" id="first_name" class="col-span-4 lg:col-span-5 bg-transparent border border-primary-50 text-primary-50 rounded-full block w-full p-2.5"/>
                </div>
                <div class="grid items-center grid-cols-6 mb-4">
                    <label for="phone" class="block col-span-2 mb-2 font-medium tracking-widest uppercase lg:col-span-1 text-primary-50 ">Phone No</label>
                    <input type="text" id="phone" class="col-span-4 lg:col-span-5 bg-transparent border border-primary-50 text-primary-50 rounded-full block w-full p-2.5"/>
                </div>
                <div class="grid items-center grid-cols-6 mb-4">
                    <label for="email" class="block col-span-2 mb-2 font-medium tracking-widest uppercase lg:col-span-1 text-primary-50">email</label>
                    <input type="email" id="email" class="col-span-4 lg:col-span-5 bg-transparent border border-primary-50 text-primary-50 rounded-full block w-full p-2.5"/>
                </div>
                <div class="grid items-center grid-cols-6">
                    <label class="block col-span-2 mb-2 font-medium tracking-widest uppercase lg:col-span-1 text-primary-50">gender</label>
                    <div class="flex col-span-4 gap-4 lg:col-span-5">
                        <div class="flex items-center">
                            <input id="default-radio-1" type="radio" value="" name="default-radio" class="w-4 h-4 border-gray-300 text-secondary-50 bg-secondary focus:ring-secondary">
                            <label for="default-radio-1" class="mt-1 uppercase text-primary-50 ms-1">Male</label>
                        </div>
                        <div class="flex items-center">
                            <input checked id="default-radio-2" type="radio" value="" name="default-radio" class="w-4 h-4 border-gray-300 text-secondary-50 bg-secondary focus:ring-secondary">
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
                    <label for="time" class="block mt-1 mb-2 uppercase text-primary-50">set date</label>
                    <div class="flex">
                        <input type="date" id="time" class="rounded-full bg-transparent border text-primary-50 leading-none block flex-1 w-full  border-primary-50 p-2.5 relative before:-z-10 before:content-['']  before:absolute before:right-0 before:w-10 before:bg-primary-50 before:block before:inset-y-0" required >
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
                <button @click="btnSkip()" class="flex items-center gap-2 p-6 tracking-widest text-white uppercase bg-primary-50">
                    <span>skip</span>
                    <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
                </button>
                <button class="flex items-center gap-2 p-6 tracking-widest text-white uppercase bg-secondary-50">
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
