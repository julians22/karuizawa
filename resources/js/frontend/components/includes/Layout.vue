<script setup>
    import { defineProps } from 'vue';

    const props = defineProps({
        csrf: String,
        user: Object,
        route_edit_profile: String,
        route_my_target: String,
        route_logout: String,
        extends: {
            type: Boolean,
            default: false
        }
    });
</script>

<template>
    <div class="grid grid-cols-8">
        <div class="flex flex-col col-span-2 w-full">
            <div :class="{ 'h-full': !props.extends }" class="flex flex-col items-center bg-cover bg-no-repeat bg-right-bottom px-7 py-20" style="background-image: url('/img/bg-02.jpg');">
                <div class="bg-primary-50 border-4 border-white border-solid rounded-full size-36 overflow-hidden">
                    <img class="w-full h-full" :src="user?.avatar" alt="">
                </div>
                <div class="mt-4 text-md text-white xl:text-xl text-center uppercase tracking-widest">
                    Hi, <br>
                    {{ user?.name }} ({{ user?.store?.name }})
                </div>
                <div class="mt-0.5 text-white text-sm text-center">Karuizawa’s Store Crew </div>
                <div class="mt-10">
                    <a class="text-white text-sm uppercase tracking-widest" :href="route_edit_profile">Edit Profile</a>
                </div>
                <div class="mt-3">
                    <a class="text-white text-sm uppercase tracking-widest" :href="route_my_target">My Target</a>
                    <div class="bg-white opacity-40 mt-3 mb-4 w-full h-0.5"></div>
                </div>
                <form method="POST" :action="route_logout">
                    <input type="hidden" name="_token" :value="csrf">
                    <button type="submit" class="text-white text-sm uppercase tracking-widest">Log out</button>
                </form>
            </div>

            <slot name="sidebar" />
        </div>
        <div class="relative col-span-6">
            <slot />
        </div>
    </div>
</template>
