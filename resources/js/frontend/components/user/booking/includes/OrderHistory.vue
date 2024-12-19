<template>
    <div>
        <div class="container pt-10 pb-28">
            <div v-for="i in 3">
                <div class="flex justify-between">
                    <div class="space-y-2 font-roboto">
                        <div class="text-xl font-bold">Booking Number: AST2220</div>
                        <div>Arief Syakur Sutedjo</div>
                        <div>Jl. Beton No 34, Kayu Putih, Pulo Gadung, Jakarta Timur, DKI Jakarta, 13210</div>
                        <div>+6281119079886</div>
                        <div>MALE</div>
                    </div>
                    <div class="space-y-3 font-roboto">
                        <div class="text-xl font-bold">Booking Time</div>
                        <div>19 Dec 2023 | 14:00</div>
                        <button class="flex items-center gap-3 px-4 py-2 tracking-widest text-white lg:py-2 lg:px-3 bg-primary-50 font-josefin">
                            <span class="mt-1 text-xs">SEE DETAIL</span>
                            <img class="inline-block size-4" src="img/icons/arrw-ck-right.png" alt="">
                        </button>
                    </div>
                </div>
                <div class="w-full h-0.5 bg-black my-6"></div>
            </div>
        </div>
    </div>
</template>
