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
                        <div class="mb-10 text-2xl tracking-wider text-center uppercase text-primary-50 font-josefin">detail order</div>

                        <div class="w-full h-[1px] bg-primary-50 my-5"></div>

                        <div class="text-lg font-bold text-center capitalize">booking number: AST2220</div>

                        <div class="w-full h-[1px] bg-primary-50 my-5"></div>

                        <div>
                            <div class="text-sm text-center capitalize">total payment</div>
                            <div class="text-xl font-light tracking-widest text-center uppercase font-josefin">idr 1.000.000</div>
                        </div>

                        <div class="w-full h-[1px] bg-primary-50 my-5"></div>


                        <div class="flex justify-center">
                            <table>
                                <tbody class="*:space-y-4">
                                    <tr class="lg:whitespace-pre-wrap">
                                        <td>Fabric</td>
                                        <td class="w-4 text-center">:</td>
                                        <td class="">E004</td>
                                    </tr>
                                    <tr class="lg:whitespace-pre-wrap">
                                        <td>Collar</td>
                                        <td class="w-4 text-center">:</td>
                                        <td class="">10. Stand</td>
                                    </tr>
                                    <tr class="lg:whitespace-pre-wrap">
                                        <td>Sleeve</td>
                                        <td class="w-4 text-center">:</td>
                                        <td class="">Short Sleeve</td>
                                    </tr>
                                    <tr class="lg:whitespace-pre-wrap">
                                        <td>Body Type</td>
                                        <td class="w-4 text-center">:</td>
                                        <td class="">
                                            <div>Front - 2. French Palette</div>
                                            <div>Back - 4. No Pleats</div>
                                            <div>Hem - Standard Hem</div>
                                        </td>
                                    </tr>
                                    <tr class="lg:whitespace-pre-wrap">
                                        <td>Pocket </td>
                                        <td class="w-4 text-center">:</td>
                                        <td class="">Round</td>
                                    </tr>
                                    <tr class="lg:whitespace-pre-wrap">
                                        <td>Button</td>
                                        <td class="w-4 text-center">:</td>
                                        <td class="">
                                            <div>Button - Purple </div>
                                            <div>Type - None</div>
                                            <div>Fabric - None </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
