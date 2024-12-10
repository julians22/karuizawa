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
            <div :class="{ 'h-full': !props.extends }" class="flex flex-col items-center py-20 bg-right-bottom bg-no-repeat bg-cover px-7" style="background-image: url('img/bg-02.jpg');">
                <div class="border-4 border-white border-solid rounded-full size-36 bg-primary-50"></div>
                <div class="mt-4 text-xl tracking-widest text-center text-white uppercase">
                    Hi, <br>
                    {{ user?.name }}
                </div>
                <div class="mt-0.5 text-sm text-center text-white">Karuizawa’s Store Crew </div>
                <div class="mt-10">
                    <a class="text-sm tracking-widest text-white uppercase" :href="route_edit_profile">Edit Profile</a>
                    <div class="h-0.5 w-full bg-white opacity-40 mt-3 mb-4"></div>
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
