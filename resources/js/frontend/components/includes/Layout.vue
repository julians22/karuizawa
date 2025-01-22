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
        <div class="flex flex-col w-full col-span-2">
            <div :class="{ 'h-full': !props.extends }" class="flex flex-col items-center py-20 bg-right-bottom bg-no-repeat bg-cover px-7" style="background-image: url('/img/bg-02.jpg');">
                <div class="pt-3 overflow-hidden border-4 border-white border-solid rounded-full bg-primary-50 size-36">
                    <img class="h-full" src="/img/default-profile.png" alt="">
                </div>
                <div class="mt-4 tracking-widest text-center text-white uppercase text-md xl:text-xl">
                    Hi, <br>
                    {{ user?.name }} ({{ user?.store?.name }})
                </div>
                <div class="mt-0.5 text-center text-sm text-white">Karuizawa’s Store Crew </div>
                <div class="mt-10">
                    <a class="text-sm tracking-widest text-white uppercase" :href="route_edit_profile">Edit Profile</a>
                    <div class="bg-white opacity-40 mt-3 mb-4 w-full h-0.5"></div>
                </div>
                <form method="POST" :action="route_logout">
                    <input type="hidden" name="_token" :value="csrf">
                    <button type="submit" class="text-sm tracking-widest text-white uppercase">Log out</button>
                </form>
            </div>

            <slot name="sidebar" />
        </div>
        <div class="relative col-span-6">
            <slot />
        </div>
    </div>
</template>
