<template>
    <div>
        <section>
            <div class="flex items-center justify-between py-7 px-14 bg-primary-50">
                <div class="text-2xl font-bold tracking-widest text-white uppercase">Customer data</div>
                <div class="tracking-widest text-white uppercase">Add Customer <span class="text-2xl font-roboto">+</span></div>
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
                    <label for="email" class="block col-span-1 mb-2 font-medium tracking-widest uppercase text-primary-50">email</label>
                    <input type="email" id="email" class="col-span-5 bg-transparent border border-primary-50 text-primary-50 rounded-full block w-full p-2.5"/>
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
        </section>
        <section class="pb-40">
            <div class="flex items-center justify-between py-7 px-14 bg-primary-50">
                <div class="text-2xl font-bold tracking-widest text-white uppercase">CONFIRM DATE & TIME</div>
            </div>
            <div class="container flex gap-10 py-10">
                <div class="flex items-center gap-6">
                    <label for="time" class="block mt-1 mb-2 uppercase text-primary-50">set date</label>
                    <div class="flex">
                        <input type="date" id="time" class="rounded-none rounded-s-full bg-transparent border text-primary-50 leading-none block flex-1 w-full  border-primary-50 p-2.5" min="09:00" max="18:00" value="00:00" required>
                        <span class="inline-flex items-center px-3 text-gray-900 border bg-primary-50 border-primary-50 rounded-s-0 border-s-0 rounded-e-full">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <label for="time" class="block mt-1 mb-2 uppercase text-primary-50">set time</label>
                    <div class="flex">
                        <input type="time" id="time" class="rounded-none rounded-s-full bg-transparent border text-primary-50 leading-none block flex-1 w-full  border-primary-50 p-2.5" min="09:00" max="18:00" value="00:00" required>
                        <span class="inline-flex items-center px-3 text-gray-900 border bg-primary-50 border-primary-50 rounded-s-0 border-s-0 rounded-e-full">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 flex">
                <button @click="$emit('btn-next', 'rtw')" class="flex items-center gap-2 p-6 tracking-widest text-white uppercase bg-primary-50">
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
