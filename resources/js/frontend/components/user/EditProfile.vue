<script setup>
    import { defineAsyncComponent, ref } from 'vue';

    const props = defineProps({
        csrf: String,
        user: Object,
        route_edit_profile: String,
        route_logout: String
    });

    const Layout = defineAsyncComponent(() => import('../utils/Layout.vue'));
</script>

<template>
    <Layout :route_edit_profile="route_edit_profile" :route_logout="route_logout" :user="user" :csrf="csrf">
        <div class="flex items-center justify-between py-7 px-14 bg-primary-50">
            <div class="text-2xl font-bold tracking-widest text-white uppercase">PERSONAL DATA</div>
        </div>

        <div class="container py-10">
            <div class="grid items-center grid-cols-6 mb-4">
                <label for="first_name" class="block col-span-1 mb-2 font-medium tracking-widest uppercase text-primary-50 ">First name</label>
                <input type="text" id="first_name" class="col-span-5 bg-transparent border border-primary-50 text-primary-50 rounded-full block w-full p-2.5"/>
            </div>
            <div class="grid items-center grid-cols-6 mb-4">
                <label for="phone" class="block col-span-1 mb-2 font-medium tracking-widest uppercase text-primary-50 ">Phone No</label>
                <input type="text" id="phone" class="col-span-5 bg-transparent border border-primary-50 text-primary-50 rounded-full block w-full p-2.5"/>
            </div>
            <div class="grid items-center grid-cols-6 mb-4">
                <label for="email" class="self-start block col-span-1 mb-2 font-medium tracking-widest uppercase text-primary-50">Address</label>
                <textarea rows="4" name="" id="" class="col-span-5 bg-transparent border border-primary-50 text-primary-50 rounded-3xl block w-full p-2.5"></textarea>
                <!-- <input type="email" id="email" class="col-span-5 bg-transparent border border-primary-50 text-primary-50 rounded-full block w-full p-2.5"/> -->
            </div>
            <div class="grid items-center grid-cols-6">
                <label class="block col-span-1 mb-2 font-medium tracking-widest uppercase text-primary-50">gender</label>
                <div class="flex col-span-5 gap-4">
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

        <!-- Change password -->
        <div class="flex items-center justify-between py-7 px-14 bg-primary-50">
            <div class="text-2xl font-bold tracking-widest text-white uppercase">Change password</div>
        </div>

        <div class="container py-10">
            <div class="grid items-center grid-cols-8 gap-4 mb-4">
                <label for="recent-pass" class="block col-span-2 mb-2 font-medium tracking-widest uppercase text-primary-50 ">recent password</label>
                <input type="password" id="recent-pass" class="col-span-6 bg-transparent border border-primary-50 text-primary-50 rounded-full block w-full p-2.5"/>
            </div>
            <div class="grid items-center grid-cols-8 gap-4 mb-4">
                <label for="new-pass" class="block col-span-2 mb-2 font-medium tracking-widest uppercase text-primary-50 ">new password</label>
                <input type="text" id="new-pass" class="col-span-6 bg-transparent border border-primary-50 text-primary-50 rounded-full block w-full p-2.5"/>
            </div>
            <div class="grid items-center grid-cols-8 gap-4 mb-4">
                <label for="confirm-pass" class="block col-span-2 mb-2 font-medium tracking-widest uppercase text-primary-50 ">CONFIRM password</label>
                <input type="text" id="confirm-pass" class="col-span-6 bg-transparent border border-primary-50 text-primary-50 rounded-full block w-full p-2.5"/>
            </div>
        </div>

        <div class="float-right">
                <button class="flex items-center gap-2 py-6 tracking-widest text-white px-14 bg-secondary-50">
                    <span class="uppercase">update</span>
                    <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
                </button>
            </div>
    </Layout>
</template>
