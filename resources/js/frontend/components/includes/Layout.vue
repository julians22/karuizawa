<script setup>
    import { defineProps } from 'vue';

    const props = defineProps({
        csrf: String,
        user: Object,
        route_edit_profile: String,
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
            <div :class="{ 'h-full': !props.extends }" class="flex flex-col items-center bg-cover bg-no-repeat bg-right-bottom px-7 py-20" style="background-image: url('img/bg-02.jpg');">
                <div class="border-4 border-white bg-primary-50 border-solid rounded-full size-36"></div>
                <div class="mt-4 text-center text-white text-xl uppercase tracking-widest">
                    Hi, <br>
                    {{ user?.name }}
                </div>
                <div class="mt-0.5 text-center text-sm text-white">Karuizawa’s Store Crew </div>
                <div class="mt-10">
                    <a class="text-sm text-white uppercase tracking-widest" :href="route_edit_profile">Edit Profile</a>
                    <div class="bg-white opacity-40 mt-3 mb-4 w-full h-0.5"></div>
                </div>
                <form method="POST" :action="route_logout">
                    <input type="hidden" name="_token" :value="csrf">
                    <button type="submit" class="text-sm text-white uppercase tracking-widest">Log out</button>
                </form>
            </div>

            <slot name="sidebar" />
        </div>
        <div class="relative col-span-6">
            <slot />
        </div>
    </div>
</template>
