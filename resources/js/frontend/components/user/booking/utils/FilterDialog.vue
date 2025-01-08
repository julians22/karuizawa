<script setup>
    import { ref, defineExpose } from 'vue';

    const dialog = ref(false);

    defineExpose({
        dialog
    })
</script>

<template>
    <dialog :open="dialog" style="z-index: 999;">
        <div class="flex items-center justify-center w-full h-full">
            <div class="relative flex-1 md:m-auto max-md:mx-10 rounded-2xl md:max-w-lg lg:max-w-xl xl:max-w-3xl 2xl:max-w-4xl">
                <div class="flex w-full h-full p-10 overflow-hidden bg-white shadow-2xl rounded-2xl max-md:flex-col">
                    <div class="absolute top-3 right-5">
                        <button @click="dialog = false"><img src="/img/icons/close.png" alt=""></button>
                    </div>
                    <div class="w-full font-roboto">
                        <div class="mb-10 text-2xl font-bold tracking-wider text-center uppercase text-primary-50 font-josefin">filter</div>
                        <div class="space-y-4">
                            <div class="grid items-center grid-cols-5 gap-4">
                                <label for="date" class="block col-span-1 font-bold texl-lg xl:text-xl whitespace-nowrap text-primary-50">By date</label>
                                <div class="flex col-span-4">
                                    <input type="date" id="date" class="rounded-full bg-transparent border text-primary-50 leading-none block flex-1 w-full  border-primary-50 p-2.5 relative before:-z-10 before:content-['']  before:absolute before:right-0 before:w-10 before:bg-primary-50 before:block before:inset-y-0" required >
                                </div>
                            </div>
                            <div class="grid items-center grid-cols-5 gap-4">
                                <label for="time" class="block col-span-1 font-bold texl-lg xl:text-xl whitespace-nowrap text-primary-50">Status</label>
                                <div class="flex col-span-4 gap-2">
                                    <button class="px-3 pt-3 pb-2 text-sm tracking-wider text-white uppercase font-josefin bg-primary-50">waiting</button>
                                    <button class="px-3 pt-3 pb-2 text-sm tracking-wider text-white uppercase font-josefin bg-primary-50">confirm order</button>
                                    <button class="px-3 pt-3 pb-2 text-sm tracking-wider text-white uppercase font-josefin bg-primary-50">rejected</button>
                                </div>
                            </div>
                        </div>
                        <div class="w-full h-[1px] bg-primary-50 my-5"></div>
                        <div class="flex justify-center gap-4">
                            <button @click="dialog = false" class="relative flex items-center gap-2 px-6 py-3 tracking-widest text-white uppercase bg-primary-50">
                                <span>cancel</span>
                                <img class="inline-block size-5" src="img/icons/arrw-ck-right.png" alt="">
                            </button>
                            <button class="relative flex items-center gap-2 px-6 py-3 tracking-widest text-white uppercase bg-secondary-50">
                                <span>apply</span>
                                <img class="inline-block size-5" src="img/icons/arrw-ck-right.png" alt="">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </dialog>
</template>

<style lang="scss" scoped>
    dialog {
        @apply fixed inset-0 h-svh w-full;
        background-color: rgba(0, 0, 0, 0.5);

        @screen md {
            align-content: center;
        }
    }
</style>
