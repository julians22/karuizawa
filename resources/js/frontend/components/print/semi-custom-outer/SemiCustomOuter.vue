<script setup>
	import { computed } from 'vue';

    import { priceFormat } from "@frontend/helpers/currency";
    import { str_limit } from '@/frontend/helpers/strlimit';

	const props = defineProps({
		data_semi_custom_outer: Object,
		data_semi_custom: Object,
		data_config: Object,
	});

	const dataSemiCustomOuter = computed(() => props.data_semi_custom_outer ?? props.data_semi_custom ?? {});
	const dataConfig = computed(() => props.data_config ?? {});
	const baseForm = computed(() => dataSemiCustomOuter.value?.basic_form ?? {});
	const sizeForm = computed(() => dataSemiCustomOuter.value?.size ?? {});

    const baseAmount = computed(() => {
        let base =  props.data_semi_custom_outer.base_price;
        let discount = props.data_semi_custom_outer.base_discount;
        let option_discount = props.data_semi_custom_outer.option_discount;

        let baseAfterDiscount = base - (base * discount / 100);

        return baseAfterDiscount - option_discount;
    });

	const sizes = computed(() => {
		const items = dataConfig.value?.sizes?.data?.basic;
		return Array.isArray(items) ? items : [];
	});

	const measurementKeys = computed(() => {
		const keys = dataConfig.value?.sizes?.measurement_key;
		return Array.isArray(keys) ? keys : [];
	});

	const collarOptions = computed(() => {
		const items = dataConfig.value?.collar?.data?.basic;
		return Array.isArray(items) ? items : [];
	});

	const cuffOptions = computed(() => {
		const items = dataConfig.value?.cuff?.data?.basic;
		return Array.isArray(items) ? items : [];
	});

	const buttonOptions = computed(() => {
		const items = dataConfig.value?.button?.data?.basic;
		return Array.isArray(items) ? items : [];
	});

	const selectedSize = computed(() => {
		return sizes.value.find((size) => size.slug === sizeForm.value?.measurement) ?? null;
	});

	const selectedSizeValues = computed(() => {
		if (selectedSize.value?.values) {
			return selectedSize.value.values;
		}

		const values = sizeForm.value?.values;
		if (values && typeof values === 'object') {
			return values;
		}

		return {};
	});

	const split = (text) => {
		if (!text) {
			return [];
		}

		return String(text).split('');
	};

	const isSelected = (selectedItem, item) => {
		return selectedItem?.slug === item?.slug;
	};

	const currencyFormat = (value) => {
		const amount = parseInt(value ?? 0, 10);
		return new Intl.NumberFormat('id-ID', {
			style: 'currency',
			currency: 'IDR',
			minimumFractionDigits: 0,
		}).format(Number.isNaN(amount) ? 0 : amount);
	};
</script>

<template>
	<div class="min-w-[1900px] pointer-events-none printable">
        <div class="bg-secondary mb-2 py-3 w-full">
            <div class="flex justify-between items-center mx-20 text-secondary-50">
                <div>
                    <img class="w-60" src="/img/brand/logo-01.png" alt="logo">
                </div>
                <div class="text-base text-center uppercase">
                    <div class="">Custom Made Outer Shirt (for men)</div>
                    <div class="bg-secondary-50 mb-0.5 h-[1px]"></div>
                    <div class="text-sm tracking-widest">Order Form</div>
                </div>
                <div>
                    <div class="text-right uppercase print:scale-150">
                        <div class="text-sm">Order No : {{ props.data_semi_custom_outer.order_number }}</div>
                        <div class="text-sm">
                            <!-- <span>Order Date : {{ moment(props.data_semi_custom_outer.order_item.order.created_at).format('DD/MM/YYYY') }}</span>
                            <span> | </span>
                            <span>
                                Option
                                <div class="inline-block">
                                    <input :checked="props.data_semi_custom.option_total > 0" class="hidden" type="radio" :id="`options`">
                                    <label class="flex items-center rounded h-full cursor-pointer" :for="`options`">
                                        <span class="checkbox-inner"></span>
                                    </label>
                                </div>
                            </span> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<div class="relative">
            <div class="gap-x-4 grid grid-cols-2">
                <div>
                    <div>
                        <div class="wrap-cat">
                            <div class="cat-name">01. FABRIC</div>
                        </div>
                        <div class="flex items-center gap-4 my-3 px-2 fabric-code print-props">
                            <label class="text-primary-outer text-sm uppercase tracking-widest whitespace-nowrap">fabric code</label>
                            <div class="flex box-input-wrapper">
                                <input
                                    v-for="(digit, index) in split(baseForm.fabric?.fabricCode)"
                                    :key="'fabric-code-' + index"
                                    type="text"
                                    maxlength="1"
                                    :value="digit"
                                    class="box-input"
                                >
                            </div>
                            <input :value="baseForm.fabric?.text ?? ''" type="text" class="block bg-white p-2 border border-primary-outer border-r w-full h-8 font-roboto text-gray-900 text-sm">
                        </div>
                    </div>
                    <div>
                        <div class="wrap-cat">
                            <div class="cat-name">02. COLLAR</div>
                        </div>
                        <div class="grid grid-cols-2 my-4 px-2 print-props">
                            <div v-for="collar in collarOptions" :key="'print-collar-' + collar.slug">
                                <input :checked="isSelected(baseForm.collar, collar)" class="hidden" type="radio" :id="'print-collar-' + collar.slug">
                                <label class="flex flex-col justify-between items-center gap-4 px-2 rounded h-full cursor-pointer" :for="'print-collar-' + collar.slug">
                                    <img class="max-w-28 h-auto" :src="`/${collar.image}`" alt="">
                                    <div class="font-bold text-primary-outer text-xs 2xl:text-lg text-center uppercase tracking-widest">{{ collar.name }}</div>
                                    <span class="checkbox-inner"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="wrap-cat">
                            <div class="cat-name">03. CUFF</div>
                        </div>
                        <div class="grid grid-cols-3 my-4 px-2 print-props">
                            <div v-for="cuff in cuffOptions" :key="'print-cuff-' + cuff.slug">
                                <input :checked="isSelected(baseForm.cuff, cuff)" class="hidden" type="radio" :id="'print-cuff-' + cuff.slug">
                                <label class="flex flex-col justify-between items-center gap-4 px-2 rounded h-full cursor-pointer" :for="'print-cuff-' + cuff.slug">
                                    <img class="max-w-28 h-auto" :src="`/${cuff.image}`" alt="">
                                    <div class="font-bold text-primary-outer text-xs 2xl:text-lg text-center uppercase tracking-widest">{{ cuff.name }}</div>
                                    <span class="checkbox-inner"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="wrap-cat">
                            <div class="cat-name">04. BUTTON</div>
                        </div>
                        <div class="grid grid-cols-5 my-4 px-2 print-props">
                            <div v-for="button in buttonOptions" :key="'print-button-' + button.slug">
                                <input :checked="isSelected(baseForm.button, button)" class="hidden" type="radio" :id="'print-button-' + button.slug">
                                <label class="flex flex-col justify-between items-center gap-4 px-2 rounded h-full cursor-pointer" :for="'print-button-' + button.slug">
                                    <img class="max-w-28 h-auto" :src="`/${button.image}`" alt="">
                                    <div class="font-bold text-primary-outer text-xs 2xl:text-lg text-center uppercase tracking-widest">{{ button.name }}</div>
                                    <span class="checkbox-inner"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div>
                        <div class="wrap-cat">
                            <div class="cat-name">05. SIZE</div>
                        </div>
                        <div class="my-4 px-2 overflow-x-auto print-props">
                            <table class="border border-primary-outer min-w-full border-collapse table-fixed">
                                <thead>
                                    <tr class="bg-white">
                                        <th></th>
                                        <th
                                            v-for="size in sizes"
                                            :key="'size-option-' + size.slug"
                                            class="px-2 py-2 border border-primary-outer text-center whitespace-nowrap"
                                        >
                                            <div class="flex justify-center">
                                                <input :checked="sizeForm.measurement === size.slug" type="radio" class="hidden" :id="'print-size-option-' + size.slug">
                                                <label class="cursor-pointer" :for="'print-size-option-' + size.slug">
                                                    <span class="checkbox-inner"></span>
                                                </label>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr class="bg-primary-outer text-white">
                                        <th class="px-2 py-2 border border-primary-outer w-36 text-xs text-left uppercase tracking-widest whitespace-nowrap">Measurement</th>
                                        <th
                                            v-for="size in sizes"
                                            :key="'size-head-' + size.slug"
                                            class="px-2 py-2 border border-primary-outer text-xs text-center uppercase tracking-widest whitespace-nowrap"
                                        >
                                            {{ size.name }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="measurement in measurementKeys"
                                        :key="'measurement-' + measurement"
                                        class="even:bg-primary-outer-100/30 odd:bg-white"
                                    >
                                        <td class="px-2 py-2 border border-primary-outer font-bold text-primary-outer text-xs whitespace-nowrap">
                                            {{ measurement }}
                                        </td>
                                        <td
                                            v-for="size in sizes"
                                            :key="'size-value-' + size.slug + '-' + measurement"
                                            class="px-2 py-2 border border-primary-outer text-primary-outer text-xs text-center whitespace-nowrap"
                                        >
                                            {{ size.values?.[measurement] ?? '-' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="grid grid-cols-2 xl:grid-cols-4 mt-4 mb-4 *:px-2 *:pt-1 *:pb-1 text-primary-outer text-xs tracking-widest whitespace-pre">
                                <div class="border-2 border-primary-outer">SPECIAL ADJUSTMENT</div>
                                <div class="flex border-primary-outer border-y-2 border-r-2">
                                    <div>SHOULDER :</div>
                                    <div>
                                        <input :value="sizeForm.sa?.shoulder ?? ''" type="text" class="bg-transparent w-full font-roboto text-xs text-center">
                                    </div>
                                </div>
                                <div class="flex border-primary-outer border-y-2 border-r-2 max-xl:border-l-2">
                                    <div>BACK LENGTH :</div>
                                    <div>
                                        <input :value="sizeForm.sa?.backLength ?? ''" type="text" class="bg-transparent w-full font-roboto text-xs text-center">
                                    </div>
                                </div>
                                <div class="flex border-primary-outer border-y-2 border-r-2">
                                    <div>SLEEVE LENGTH :</div>
                                    <div>
                                        <input :value="sizeForm.sa?.sleeveLength ?? ''" type="text" class="bg-transparent w-full font-roboto text-xs text-center">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="mb-0">
                            <div class="wrap-cat">
                                <div class="cat-name">ADDITIONAL NOTES</div>
                            </div>
                            <div class="gap-3 grid grid-cols-5 my-4 px-2">
                                <div class="col-span-6">
                                    <textarea v-model="additionalNote" class="p-2 border-2 border-primary-outer w-full h-full font-roboto placeholder:font-josefin print:text-2xl placeholder:tracking-widest placeholder-primary-50" id="" placeholder="NOTE"></textarea>
                                </div>
                                <div class="col-span-5 bg-secondary p-2 w-full">
                                    <div class="w-full">
                                        <div class="text-primary-outer text-sm uppercase tracking-widest">Total price</div>
                                        <div class="items-center grid grid-cols-[repeat(27,1fr)] w-full text-center uppercase">
                                            <div class="flex flex-col col-span-6 bg-white border-2 border-primary-outer">
                                                <div class="bg-primary-outer py-0.5 text-white text-sm text-center">price</div>
                                                <div class="flex flex-col justify-center items-center px-2 py-1 h-14">
                                                    <div class="font-roboto capitalize print-props">{{ priceFormat(baseAmount) }}</div>
                                                    <small class="text-[8px]">(base, discount, GC)</small>
                                                </div>
                                            </div>
                                            <div class="col-span-1">+</div>
                                            <div class="flex flex-col col-span-6 bg-white border-2 border-primary-outer">
                                                <div class="bg-primary-outer py-0.5 text-white text-sm text-center">option</div>
                                                <div class="flex justify-center items-center px-2 py-1 h-14">
                                                    <div class="font-roboto capitalize print-props">{{ priceFormat(optionAmount) }}</div>
                                                </div>
                                            </div>
                                            <div class="col-span-1">+</div>
                                            <div class="flex flex-col col-span-6 bg-white border-2 border-primary-outer">
                                                <div class="bg-primary-outer py-0.5 text-white text-sm text-center">delivery cost</div>
                                                <div class="flex justify-center items-center px-2 py-1 h-14">
                                                    <div></div>
                                                </div>
                                            </div>
                                            <div class="col-span-1">=</div>
                                            <div class="flex flex-col col-span-6 bg-white border-2 border-primary-outer">
                                                <div class="bg-primary-outer py-0.5 text-white text-sm text-center">total</div>
                                                <div class="flex justify-center items-center px-2 py-1 h-14">
                                                    <div></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="gap-2 grid grid-cols-5 mt-2">
                                        <div class="col-span-3">
                                            <table class="w-full">
                                                <thead>
                                                    <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-outer">
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
                                                    <tr class="*:px-2 *:pt-2 *:pb-1 *:border-primary-outer *:border-x">
                                                        <th>Name</th>
                                                        <th class="w-full print-props">{{ props.data_semi_custom_outer.customer.full_name }}</th>
                                                    </tr>
                                                    <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-outer">
                                                        <th>address</th>
                                                        <th class="w-full print-props">{{ str_limit(props.data_semi_custom_outer.customer.address, 60) }}</th>
                                                    </tr>
                                                    <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-outer">
                                                        <th>tel / hp</th>
                                                        <th class="w-full print-props">{{ props.data_semi_custom_outer.customer.phone }}</th>
                                                    </tr>
                                                    <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-outer">
                                                        <th>email</th>
                                                        <th class="w-full print-props">{{ props.data_semi_custom_outer.customer.email }}</th>
                                                    </tr>
                                                    <tr class="*:px-2 *:pt-2 *:pb-1 *:border *:border-primary-outer">
                                                        <th class="whitespace-nowrap">handling date</th>
                                                        <th class="w-full print-props">
                                                            {{ props.data_semi_custom_outer.handling_date }}
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="flex flex-col justify-between col-span-2 uppercase">
                                            <div class="flex flex-col col-span-6 bg-white border-2 border-primary-outer">
                                                <div class="bg-primary-outer py-0.5 text-white text-sm text-center">customer sign</div>
                                                <div class="flex justify-center items-center px-2 py-1 h-16 print:h-24">
                                                    <div></div>
                                                </div>
                                            </div>
                                            <div class="flex flex-col col-span-6 bg-white border-2 border-primary-outer">
                                                <div class="bg-primary-outer py-0.5 text-white text-sm text-center">store sign</div>
                                                <div class="flex justify-center items-center px-2 py-1 h-16 print:h-24">
                                                    <div class="print-props">
                                                        {{ props.data_semi_custom_outer.order_item.order.user.name }}

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>
</template>

<style scoped>
	input[type="radio"] + label span.checkbox-inner {
		@apply border-primary-outer;
	}

	input[type="radio"]:checked + label span.checkbox-inner {
		@apply bg-primary-outer border-primary-outer;
		color: #fff;
		background-image: url("data:image/svg+xml,%3C%3Fxml version='1.0' encoding='UTF-8'%3F%3E%3Csvg width='14px' height='10px' viewBox='0 0 14 10' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'%3E%3Ctitle%3Echeck%3C/title%3E%3Cg id='Page-1' stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'%3E%3Cg id='ios_modification' transform='translate(-27.000000, -191.000000)' fill='%23FFFFFF' fill-rule='nonzero'%3E%3Cg id='Group-Copy' transform='translate(0.000000, 164.000000)'%3E%3Cg id='ic-check-18px' transform='translate(25.000000, 23.000000)'%3E%3Cpolygon id='check' points='6.61 11.89 3.5 8.78 2.44 9.84 6.61 14 15.56 5.05 14.5 4'%3E%3C/polygon%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
		background-size: 14px 10px;
	}

    .checkbox-inner {
        @apply flex justify-center items-center border border-primary-outer size-5 text-transparent;
		background: transparent no-repeat center;
	}

	.box-input-wrapper {
		@apply flex font-roboto;
	}

    .box-input-wrapper .box-input {
        @apply block bg-white p-1 border border-primary-outer size-7 font-roboto text-gray-900 text-xs text-center;
	}

	.box-input-wrapper .box-input:not(:first-child) {
		@apply border-y border-r border-l-0;
	}

	.textarea,
	textarea,
	input {
		outline: none;
	}

    .wrap-cat{
        @apply flex justify-between items-center bg-primary-outer-300 px-4 py-1;

        .cat-name {
            @apply font-bold tracking-widest text-white uppercase
        }

    }

    .label-name {
        @apply font-bold text-primary-outer text-xs print:text-xl text-center uppercase tracking-widest;
    }

    input[type="radio"] + label span.checkbox-inner {
        @apply print:border-primary-outer;
    }
    input[type="radio"]:checked + label span.checkbox-inner {
        @apply bg-primary-outer border-primary-outer;
        color: #fff;
        background-image: url("data:image/svg+xml,%3C%3Fxml version='1.0' encoding='UTF-8'%3F%3E%3Csvg width='14px' height='10px' viewBox='0 0 14 10' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'%3E%3C!-- Generator: Sketch 59.1 (86144) - https://sketch.com --%3E%3Ctitle%3Echeck%3C/title%3E%3Cdesc%3ECreated with Sketch.%3C/desc%3E%3Cg id='Page-1' stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'%3E%3Cg id='ios_modification' transform='translate(-27.000000, -191.000000)' fill='%23FFFFFF' fill-rule='nonzero'%3E%3Cg id='Group-Copy' transform='translate(0.000000, 164.000000)'%3E%3Cg id='ic-check-18px' transform='translate(25.000000, 23.000000)'%3E%3Cpolygon id='check' points='6.61 11.89 3.5 8.78 2.44 9.84 6.61 14 15.56 5.05 14.5 4'%3E%3C/polygon%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        background-size: 14px 10px;
    }
    .checkbox-inner {
        @apply flex justify-center items-center border border-primary-outer size-5 text-transparent;
        background: transparent no-repeat center;
    }
</style>
