<script setup>
    import { computed } from 'vue';
    import { priceFormat } from "@frontend/helpers/currency";
    import { str_limit } from '@/frontend/helpers/strlimit';
    import InputBox from '@frontend/components/utils/fields/InputBox.vue';

    import moment from 'moment';

    const props = defineProps({
        data_semi_custom_light_jacket: Object,
        data_config: Object,
    });

    const data    = computed(() => props.data_semi_custom_light_jacket ?? {});
    const cfg     = computed(() => props.data_config ?? {});
    const baseForm = computed(() => data.value?.basic_form ?? {});
    const sizeForm = computed(() => data.value?.size ?? {});
    const optionForm = computed(() => data.value?.option_form ?? {});

    const address = computed(() =>
        data.value?.address ?? data.value?.customer?.address ?? '-'
    );

    const baseAmount = computed(() => {
        const base     = data.value?.base_price     ?? 0;
        const discount = data.value?.base_discount  ?? 0;
        return base - (base * discount / 100);
    });

    const optionAmount = computed(() => data.value?.option_total ?? 0);

    // Config helpers
    const buttonOptions = computed(() => cfg.value?.button?.data?.basic ?? []);
    const buttonHoleOptions = computed(() => cfg.value?.button_hole?.data?.option?.option_1 ?? []);
    const buttonSewingOptions = computed(() => cfg.value?.button_sewing?.data?.option?.option_1 ?? []);
    const embroideryFonts  = computed(() => cfg.value?.embroidery?.data?.options?.fonts  ?? []);
    const embroideryColors = computed(() => cfg.value?.embroidery?.data?.options?.colors ?? []);

    const sizes = computed(() => cfg.value?.sizes?.data?.basic ?? []);
    const measurementKeys = computed(() => cfg.value?.sizes?.measurement_key ?? []);
    const actualMeasurementKeys = computed(() => cfg.value?.sizes?.actual_measurement_key ?? []);

    const hasEmbroidery = computed(() => !!baseForm.value?.embroidery?.price && baseForm.value.embroidery.price > 0 || !!optionForm.value?.embroidery);

    const isSelected = (selectedSlug, item) => selectedSlug === item?.slug;

    const split = (text) => text ? String(text).split('') : [];
</script>

<template>
    <div class="min-w-[1900px] pointer-events-none printable">

        <!-- ── HEADER ── -->
        <div class="bg-secondary mb-2 py-3 w-full">
            <div class="flex justify-between items-center mx-20 text-secondary-50">
                <div>
                    <img class="w-60" src="/img/brand/logo-01.png" alt="logo">
                </div>
                <div class="text-base text-center uppercase">
                    <div>Custom Made Light Jacket (for men)</div>
                    <div class="bg-secondary-50 mb-0.5 h-[1px]"></div>
                    <div class="text-sm tracking-widest">Order Form</div>
                </div>
                <div class="text-right uppercase">
                    <div class="text-sm">Order No : {{ data.order_number }}</div>
                    <div class="text-sm">
                        Order Date : {{ moment(data.order_item?.order?.created_at).format('DD/MM/YYYY') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- ── BODY ── -->
        <div class="relative">
            <div class="gap-x-4 grid grid-cols-2">

                <!-- ════ LEFT COLUMN ════ -->
                <div>

                    <!-- 01. FABRIC -->
                    <div>
                        <div class="wrap-cat">
                            <div class="cat-name">01. FABRIC</div>
                        </div>
                        <div class="flex items-center gap-4 my-3 px-2 fabric-code print-props">
                            <label class="text-primary-light-jacket uppercase tracking-widest whitespace-nowrap">fabric code</label>
                            <div class="flex box-input-wrapper">
                                <input
                                    v-for="(digit, index) in split(baseForm.fabric?.fabricCode)"
                                    :key="'fc-' + index"
                                    type="text"
                                    maxlength="1"
                                    :value="digit"
                                    class="box-input"
                                >
                            </div>
                            <input
                                :value="baseForm.fabric?.text ?? ''"
                                type="text"
                                class="block bg-white p-2 border border-primary-light-jacket w-full h-8 font-roboto text-gray-900 text-sm"
                            >
                        </div>
                    </div>

                    <!-- 02. BUTTON -->
                    <div>
                        <div class="wrap-cat">
                            <div class="cat-name">02. BUTTON</div>
                        </div>
                        <div class="grid grid-cols-5 my-4 px-2 print-props">
                            <div v-for="btn in buttonOptions" :key="'print-btn-' + btn.slug">
                                <input
                                    :checked="isSelected(baseForm.button?.slug, btn)"
                                    class="hidden" type="radio"
                                    :id="'print-btn-' + btn.slug"
                                >
                                <label class="flex flex-col justify-between items-center gap-2 px-1 rounded h-full cursor-pointer" :for="'print-btn-' + btn.slug">
                                    <!-- <img class="w-full max-w-20 h-auto" :src="`/${btn.image}`" alt=""> -->
                                    <div class="font-bold text-primary-light-jacket text-xs text-center uppercase tracking-widest">{{ btn.name }}</div>
                                    <span class="checkbox-inner"></span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mx-10 my-3">
                            <div class="font-bold text-primary-50 text-xs uppercase tracking-widest">OPTION</div>
                            <div class="flex font-roboto print-props">
                                <InputBox :digitCount="2" :inputValue="baseForm.button?.optionNumber" />
                            </div>
                        </div>
                    </div>

                    <!-- SIZE TABLE -->
                    <div>
                        <div class="wrap-cat">
                            <div class="cat-name">SIZE</div>
                        </div>
                        <div class="my-4 px-1 overflow-x-auto print-props">
                            <table class="border border-primary-light-jacket min-w-full border-collapse table-fixed">
                                <thead>
                                    <tr class="bg-white">
                                        <th></th>
                                        <th
                                            v-for="size in sizes"
                                            :key="'size-opt-' + size.slug"
                                            class="px-1 py-1 border border-primary-light-jacket w-16 text-center whitespace-nowrap"
                                        >
                                            <div class="flex justify-center">
                                                <input
                                                    :checked="sizeForm.measurement === size.slug"
                                                    type="radio" class="hidden"
                                                    :id="'print-size-' + size.slug"
                                                >
                                                <label class="cursor-pointer" :for="'print-size-' + size.slug">
                                                    <span class="checkbox-inner"></span>
                                                </label>
                                            </div>
                                        </th>
                                        <th rowspan="2" class="px-1 py-1 border border-primary-light-jacket text-xs text-center uppercase tracking-widest whitespace-nowrap">
                                            Actual<br>Measurement
                                        </th>
                                        <th rowspan="2" class="px-1 py-1 border border-primary-light-jacket text-xs text-center uppercase tracking-widest whitespace-nowrap">
                                            Adjustment
                                        </th>
                                        <!-- Max Size -->
                                         <th rowspan="2" class="px-1 py-1 border border-primary-light-jacket text-xs text-center uppercase tracking-widest whitespace-nowrap">
                                            Max <br> Range
                                        </th>
                                    </tr>
                                    <tr class="bg-primary-light-jacket text-white">
                                        <th class="px-1 py-1 border border-primary-light-jacket w-28 text-xs text-left uppercase tracking-widest whitespace-nowrap">Measurement</th>
                                        <th
                                            v-for="size in sizes"
                                            :key="'size-hd-' + size.slug"
                                            class="px-1 py-1 border border-primary-light-jacket text-xs text-center uppercase tracking-widest whitespace-nowrap"
                                        >
                                            {{ size.name }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="key in measurementKeys"
                                        :key="'mkey-' + key"
                                        class="even:bg-primary-light-jacket-100/30 odd:bg-white"
                                    >
                                        <td class="px-1 py-1 border border-primary-light-jacket font-bold text-primary-light-jacket text-base whitespace-nowrap">
                                            {{ key }}
                                        </td>
                                        <td
                                            v-for="size in sizes"
                                            :key="'sval-' + size.slug + '-' + key"
                                            class="px-1 py-1 border border-primary-light-jacket text-primary-light-jacket text-base text-center whitespace-nowrap"
                                        >
                                            <span v-if="sizeForm.measurement === size.slug" class="font-roboto font-bold">
                                                {{ sizeForm.measurement_values?.[key] ?? size.values?.[key] ?? '-' }}
                                            </span>
                                            <span v-else>
                                                {{ size.values?.[key] ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-1 py-1 border border-primary-light-jacket text-center uppercase tracking-widest whitespace-nowrap">
                                            {{ sizeForm.actualMeasurement?.values?.[key] ?? '-' }}
                                        </td>
                                        <td class="px-1 py-1 border border-primary-light-jacket text-center whitespace-nowrap">
                                            {{  key === 'Shoulder Width' ? sizeForm.sa?.shoulder      :
                                                key === 'Waist'          ? sizeForm.sa?.waist         :
                                                key === 'Back Length'    ? sizeForm.sa?.backLength     :
                                                key === 'Sleeve Length'  ? sizeForm.sa?.sleeveLength   : '-' }}
                                        </td>
                                        <td class="px-1 py-1 border border-primary-light-jacket text-center whitespace-nowrap">
                                            {{ key === 'Shoulder Width' ? "∓2 cm" :
                                                  key === 'Waist'    ? "∓6 cm" :
                                                    key === 'Back Length'    ? "∓4 cm" :
                                                        key === 'Sleeve Length'  ? "∓10 cm" : '-' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Order type -->
                        <div class="gap-2 grid grid-cols-3 mb-4 px-2">
                            <div>
                                <input :checked="sizeForm.order === '1. NEW ORDER'" class="hidden" type="radio" value="1. NEW ORDER" id="lj-new-order">
                                <label class="flex items-center gap-3 px-2 rounded h-full cursor-pointer" for="lj-new-order">
                                    <div class="label-name">1. NEW ORDER</div>
                                    <span class="checkbox-inner"></span>
                                </label>
                            </div>
                            <div>
                                <input :checked="sizeForm.order === '2. REPEAT ORDER'" class="hidden" type="radio" value="2. REPEAT ORDER" id="lj-repeat-order">
                                <label class="flex items-center gap-3 px-2 rounded h-full cursor-pointer" for="lj-repeat-order">
                                    <div class="label-name">2. REPEAT ORDER</div>
                                    <span class="checkbox-inner"></span>
                                </label>
                            </div>
                            <div>
                                <input :checked="sizeForm.order === '3. GARMENT SAMPLE'" class="hidden" type="radio" value="3. GARMENT SAMPLE" id="lj-garment-sample">
                                <label class="flex items-center gap-3 px-2 rounded h-full cursor-pointer" for="lj-garment-sample">
                                    <div class="label-name">3. GARMENT SAMPLE</div>
                                    <span class="checkbox-inner"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- NOTES + PRICING + CUSTOMER -->
                    <div class="mt-2">
                        <div class="wrap-cat">
                            <div class="cat-name">ADDITIONAL NOTES</div>
                        </div>
                        <div class="gap-3 grid grid-cols-5 my-4 px-2">
                            <div class="col-span-6">
                                <textarea
                                    :value="data.base_note ?? ''"
                                    class="p-2 border-2 border-primary-light-jacket w-full h-20 font-roboto print:text-2xl placeholder:tracking-widest placeholder-primary-50"
                                    placeholder="NOTE"
                                ></textarea>
                            </div>

                            <!-- Pricing block -->
                            <div class="col-span-5 bg-secondary p-2 w-full">
                                <div class="w-full">
                                    <div class="text-primary-light-jacket text-sm uppercase tracking-widest">Total price</div>
                                    <div class="items-center grid grid-cols-[repeat(27,1fr)] w-full text-center uppercase">
                                        <div class="flex flex-col col-span-6 bg-white border-2 border-primary-light-jacket">
                                            <div class="bg-primary-light-jacket py-0.5 text-white text-sm text-center">price</div>
                                            <div class="flex flex-col justify-center items-center px-2 py-1 h-14">
                                                <div class="font-roboto capitalize print-props">{{ priceFormat(baseAmount) }}</div>
                                                <small class="text-[8px]">(base, discount)</small>
                                            </div>
                                        </div>
                                        <div class="col-span-1">+</div>
                                        <div class="flex flex-col col-span-6 bg-white border-2 border-primary-light-jacket">
                                            <div class="bg-primary-light-jacket py-0.5 text-white text-sm text-center">option</div>
                                            <div class="flex justify-center items-center px-2 py-1 h-14">
                                                <div class="font-roboto capitalize print-props">{{ priceFormat(optionAmount) }}</div>
                                            </div>
                                        </div>
                                        <div class="col-span-1">+</div>
                                        <div class="flex flex-col col-span-6 bg-white border-2 border-primary-light-jacket">
                                            <div class="bg-primary-light-jacket py-0.5 text-white text-sm text-center">delivery cost</div>
                                            <div class="flex justify-center items-center px-2 py-1 h-14"></div>
                                        </div>
                                        <div class="col-span-1">=</div>
                                        <div class="flex flex-col col-span-6 bg-white border-2 border-primary-light-jacket">
                                            <div class="bg-primary-light-jacket py-0.5 text-white text-sm text-center">total</div>
                                            <div class="flex justify-center items-center px-2 py-1 h-14"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="gap-2 grid grid-cols-5 mt-2">
                                    <div class="col-span-3">
                                        <table class="w-full">
                                            <thead>
                                                <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-light-jacket">
                                                    <th>membership number</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <table>
                                            <thead class="text-sm text-left uppercase">
                                                <tr class="*:px-2 *:pt-2 *:pb-1 *:border-primary-light-jacket *:border-x">
                                                    <th>Name</th>
                                                    <th class="w-full print-props">{{ data.customer?.full_name }}</th>
                                                </tr>
                                                <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-light-jacket">
                                                    <th>address</th>
                                                    <th class="w-full print-props">{{ str_limit(address, 500) }}</th>
                                                </tr>
                                                <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-light-jacket">
                                                    <th>telp / hp</th>
                                                    <th class="w-full print-props">{{ data.customer?.phone }}</th>
                                                </tr>
                                                <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-light-jacket">
                                                    <th>email</th>
                                                    <th class="w-full print-props">{{ data.customer?.email }}</th>
                                                </tr>
                                                <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-light-jacket">
                                                    <th class="whitespace-nowrap">handling date</th>
                                                    <th class="w-full print-props">{{ data.handling_date }}</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="flex flex-col justify-between col-span-2 uppercase">
                                        <div class="flex flex-col bg-white border-2 border-primary-light-jacket">
                                            <div class="bg-primary-light-jacket py-0.5 text-white text-sm text-center">customer sign</div>
                                            <div class="flex justify-center items-center px-2 py-1 h-16 print:h-24"></div>
                                        </div>
                                        <div class="flex flex-col bg-white border-2 border-primary-light-jacket">
                                            <div class="bg-primary-light-jacket py-0.5 text-white text-sm text-center">store sign</div>
                                            <div class="flex justify-center items-center px-2 py-1 h-16 print:h-24">
                                                <div class="print-props">{{ data.order_item?.order?.user?.name }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- ════ RIGHT COLUMN ════ -->
                <div>

                    <!-- 03. BUTTON HOLE -->
                    <div>
                        <div class="wrap-cat">
                            <div class="cat-name">BUTTON HOLE</div>
                        </div>
                        <div class="gap-2 grid grid-cols-6 my-4 px-2 print-props">
                            <div v-for="opt in buttonHoleOptions" :key="'print-bh-' + opt.slug">
                                <input
                                    :checked="isSelected(baseForm.button_hole?.slug, opt)"
                                    class="hidden" type="radio"
                                    :id="'print-bh-' + opt.slug"
                                >
                                <label class="flex flex-col justify-between items-center gap-1 px-1 rounded h-full cursor-pointer" :for="'print-bh-' + opt.slug">
                                    <template v-if="opt.image">
                                        <img class="w-full max-w-24 h-auto" :src="`/${opt.image}`" alt="">
                                    </template>
                                    <template v-else>
                                        <div class="flex justify-center items-center bg-gray-100 border border-primary-light-jacket/40 size-8"></div>
                                    </template>
                                    <div class="font-bold text-primary-light-jacket text-xs text-center uppercase leading-tight tracking-widest">{{ opt.name }}</div>
                                    <span class="checkbox-inner-sm"></span>
                                </label>
                            </div>
                        </div>
                        <div v-if="baseForm.button_hole?.label" class="px-3 pb-2 text-gray-500 text-xs italic">
                            {{ baseForm.button_hole.label }}
                        </div>
                    </div>

                    <!-- 04. BUTTON SEWING -->
                    <div>
                        <div class="wrap-cat">
                            <div class="cat-name">BUTTON THREAD</div>
                        </div>
                        <div class="gap-2 grid grid-cols-6 my-4 px-2 print-props">
                            <div v-for="opt in buttonSewingOptions" :key="'print-bs-' + opt.slug">
                                <input
                                    :checked="isSelected(baseForm.button_sewing?.slug, opt)"
                                    class="hidden" type="radio"
                                    :id="'print-bs-' + opt.slug"
                                >
                                <label class="flex flex-col justify-between items-center gap-1 px-1 rounded h-full cursor-pointer" :for="'print-bs-' + opt.slug">
                                    <template v-if="opt.image">
                                        <img class="w-full max-w-20 h-auto" :src="`/${opt.image}`" alt="">
                                    </template>
                                    <template v-else>
                                        <div class="flex justify-center items-center bg-gray-100 border border-primary-light-jacket/40 size-8"></div>
                                    </template>
                                    <div class="font-bold text-primary-light-jacket text-sm text-center uppercase leading-tight tracking-widest">{{ opt.name }}</div>
                                    <span class="checkbox-inner-sm"></span>
                                </label>
                            </div>
                        </div>
                        <div v-if="baseForm.button_sewing?.label" class="px-3 pb-2 text-gray-500 text-sm italic">
                            {{ baseForm.button_sewing.label }}
                        </div>
                    </div>


                    <!-- 05. EMBROIDERY -->
                    <div>
                        <div class="wrap-cat">
                            <div class="mb-2 cat-name">
                                05. EMBROIDERY
                            </div>
                        </div>
                        <div class="gap-4 grid grid-cols-1 px-2 py-2">
                            <!-- Font type -->
                            <div>
                                <div class="sub-cat-name">Font Type</div>
                                <div class="gap-2 grid grid-cols-3 py-2 print-props">
                                    <div v-for="font in embroideryFonts" :key="'lj-font-' + font.slug">
                                        <input
                                            :checked="isSelected(baseForm.embroidery?.fontType?.slug, font)"
                                            class="hidden" type="radio"
                                            :id="'lj-font-' + font.slug"
                                        >
                                        <label class="flex flex-col items-center gap-1 cursor-pointer" :for="'lj-font-' + font.slug">
                                            <div class="text-primary-light-jacket text-sm text-center uppercase tracking-widest">{{ font.name }}</div>
                                            <span class="checkbox-inner-sm"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- Color -->
                            <div>
                                <div class="sub-cat-name">Font Color</div>
                                <div class="gap-1 grid grid-cols-6 print-props">
                                    <div v-for="color in embroideryColors" :key="'lj-color-' + color.slug">
                                        <input
                                            :checked="isSelected(baseForm.embroidery?.color?.slug, color)"
                                            class="hidden" type="radio"
                                            :id="'lj-color-' + color.slug"
                                        >
                                        <label class="flex flex-col items-center gap-0.5 cursor-pointer" :for="'lj-color-' + color.slug">
                                            <img class="size-14 object-contain" :src="`/${color.image}`" alt="">
                                            <div class="text-primary-light-jacket text-sm text-center uppercase leading-tight tracking-widest">{{ color.name }}</div>
                                            <span class="checkbox-inner-sm"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Embroidery text -->
                            <div>
                                <div class="sub-cat-name">Embroidery Text</div>
                                <div class="flex items-end font-roboto print-props">
                                    <!-- Initial: X . Y -->
                                    <div class="flex items-end">
                                        <input
                                            :value="baseForm.embroidery?.initialName?.x ?? ''"
                                            type="text" maxlength="1"
                                            class="block p-2 border border-primary-light-jacket size-10 text-gray-900 text-base text-center"
                                        >
                                        <input
                                            :value="baseForm.embroidery?.initialName?.dot ?? ''"
                                            type="text" maxlength="1"
                                            class="block border-primary-light-jacket border-y w-8 h-6 text-gray-900 text-base text-center"
                                        >
                                        <input
                                            :value="baseForm.embroidery?.initialName?.y ?? ''"
                                            type="text" maxlength="1"
                                            class="block p-2 border border-primary-light-jacket size-10 text-gray-900 text-base text-center"
                                        >
                                    </div>
                                    <!-- Long name / Z: individual boxes -->
                                    <div class="flex items-end box-input-wrapper">
                                        <input
                                            v-for="(digit, index) in split(baseForm.embroidery?.initialName?.z ?? '')"
                                            :key="'emb-z-' + index"
                                            type="text" maxlength="1"
                                            :value="digit"
                                            class="box-input"
                                        >
                                        <template v-if="(baseForm.embroidery?.initialName?.z ?? '').length < 12">
                                            <input
                                                v-for="n in (12 - (baseForm.embroidery?.initialName?.z ?? '').length)"
                                                :key="'emb-z-empty-' + n"
                                                type="text" maxlength="1"
                                                value=""
                                                class="box-input"
                                            >
                                        </template>
                                        <input
                                            :value="baseForm.embroidery?.initialName?.note ?? ''"
                                            type="text" maxlength="50"
                                            class="block ml-2 p-2 border border-primary-light-jacket w-full text-gray-900 text-base"
                                        >
                                    </div>
                                </div>
                                <div class="mt-1 font-roboto text-[10px] text-primary-light-jacket/70 italic">
                                    *write your initial (font type 1, 2, 3) or long name into the boxes
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- ════ end RIGHT ════ -->

            </div>
        </div>

    </div>
</template>

<style scoped>
    input[type="radio"] + label span.checkbox-inner,
    input[type="radio"] + label span.checkbox-inner-sm {
        @apply border-primary-light-jacket;
    }

    input[type="radio"]:checked + label span.checkbox-inner,
    input[type="radio"]:checked + label span.checkbox-inner-sm {
        @apply bg-primary-light-jacket border-primary-light-jacket;
        color: #fff;
        background-image: url("data:image/svg+xml,%3C%3Fxml version='1.0' encoding='UTF-8'%3F%3E%3Csvg width='14px' height='10px' viewBox='0 0 14 10' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'%3E%3Ctitle%3Echeck%3C/title%3E%3Cg id='Page-1' stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'%3E%3Cg id='ios_modification' transform='translate(-27.000000, -191.000000)' fill='%23FFFFFF' fill-rule='nonzero'%3E%3Cg id='Group-Copy' transform='translate(0.000000, 164.000000)'%3E%3Cg id='ic-check-18px' transform='translate(25.000000, 23.000000)'%3E%3Cpolygon id='check' points='6.61 11.89 3.5 8.78 2.44 9.84 6.61 14 15.56 5.05 14.5 4'%3E%3C/polygon%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        background-size: 14px 10px;
    }

    .checkbox-inner {
        @apply flex justify-center items-center border border-primary-light-jacket size-9 text-transparent;
        background: transparent no-repeat center;
    }

    .checkbox-inner-sm {
        @apply flex justify-center items-center border border-primary-light-jacket size-7 text-transparent;
        background: transparent no-repeat center;
    }

    .box-input-wrapper {
        @apply flex font-roboto;
    }

    .box-input-wrapper .box-input {
        @apply block bg-white p-1 border border-primary-light-jacket size-9 font-roboto text-gray-900 text-center;
    }

    .box-input-wrapper .box-input:not(:first-child) {
        @apply border-y border-r border-l-0;
    }

    .wrap-cat {
        @apply flex justify-between items-center bg-primary-light-jacket-100 px-4 py-1;

        .cat-name {
            @apply flex items-center gap-2 font-bold text-white uppercase tracking-widest;
        }
    }

    .sub-cat-name {
        @apply bg-primary-light-jacket-100 mb-2 px-2 py-1 font-bold text-white text-sm uppercase tracking-widest;
    }

    .label-name {
        @apply font-bold text-primary-light-jacket text-xs print:text-xl text-center uppercase tracking-widest;
    }

    textarea, input {
        outline: none;
    }
</style>
