<script setup>
import { ref } from 'vue';

const date = ref('');

const print = () => {
    if (!date.value) {
        return;
    }else{
        window.open(
        `/print-sc-per-day?date=${date.value}`,
        '_blank' // <- This is what makes it open in a new window.
        );
    }
}

</script>

<template>
    <div class="flex flex-col items-center justify-center gap-8 mt-20">
        <div class="space-y-2">
            <label for="date" class="block font-bold text-center up text-primary-50 xl:text-xl whitespace-nowrap texl-lg">By date</label>
            <div class="flex w-60">
                <input type="date" id="date" v-model="date" class="block before:block relative before:right-0 before:-z-10 before:absolute before:inset-y-0 flex-1 before:content-[''] border-primary-50 bg-transparent before:bg-primary-50 p-2.5 border rounded-full w-full before:w-10 text-primary-50 leading-none" required >
            </div>
        </div>
        <div>
            <button @click="print()" class="px-4 py-2 text-white uppercase bg-primary-50">Print</button>
        </div>
    </div>
</template>
