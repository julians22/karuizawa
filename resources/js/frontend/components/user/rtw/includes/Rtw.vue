<script setup>
    import { defineEmits, onMounted, ref, watch, nextTick, reactive } from 'vue';
    import { TailwindPagination } from 'laravel-vue-pagination';
    import { useProducts } from '../../../../store/product';
    import { priceFormat } from '../../../../helpers/currency';
    import { useCustomer } from '../../../../store/customer';
    import { useUser } from '../../../../store/user';


    const isLoading = ref(true);

    const props = defineProps({
        api_product_url: String,
    });

    const products = ref({});

    const storeProducts = useProducts();

    const form = ref({
        shirtsSelected: [],
    });

    const keywords = ref('');

    const sizes = [
        'XS', 'S', 'M', 'L', 'XL', 'XXL'
    ];

    const colours = [
        'Red', 'Blue', 'Green', 'Yellow', 'Black', 'White', 'Gold', 'Silver'
    ];

    const sorts = [
        'Newest', 'Oldest', 'Price: Low to High', 'Price: High to Low'
    ];

    const filters = reactive({
        size: '0',
        color: '0',
        sort: '0',
    });

    const selectedProducts = ref([]);

    watch(form.value, () => {
        storeProducts.setSlug = form.value.shirtsSelected;
    });

    watch(filters, () => {
        fetchProducts(1);
    });

    const selectProducts = async () => {
        await axios.get(`/api/find-product/${form.value.shirtsSelected}`)
            .then(response => {
                selectedProducts.value = response.data;
                selectedProducts.value.forEach(shirt => {
                    shirt.qty = 1;
                });

                storeProducts.setProducts = selectedProducts.value;
            })
            .catch(error => {
                console.error('There was an error!', error.response.data.message);
            });

            return;
    }

    onMounted(() => {
        nextTick(() => {
            if (storeProducts.setSlug.length) {
                form.value.shirtsSelected = storeProducts.getSlug;
            }else {
                storeProducts.setSlug = form.value.shirtsSelected;
            }
        });
        fetchProducts(1);
    });

    const getResults = async (page = 1) => {
        fetchProducts(page);
    }

    const searchProduct = () => {
        fetchProducts(1);
    }

    const fetchProducts = async (page) => {

        isLoading.value = true;

        let url = `${props.api_product_url}?page=${page}`;

        let store_id = await useUser().getStoreId;

        const keywordString = keywords.value ? `&search=${keywords.value}` : '&search=';
        if (filters.size !== '0') {
            url = `${url}&size=${filters.size}`;
        }

        if (filters.color !== '0') {
            url = `${url}&color=${filters.color}`;
        }

        if (filters.sort !== '0') {
            url = `${url}&sort=${filters.sort}`;
        }

        if (store_id) {
            url = `${url}&store_id=${store_id}`;
        }

        url = `${url}${keywordString}`;

        fetch(`${url}`)
            .then(response => response.json())
            .then(data => {
                products.value = data;
            })
            .catch(error => {
                console.error('There was an error!', error);
            })
            .finally(() => {
                isLoading.value = false;
            });
    }

    const formError = ref([]);

    const $emit = defineEmits(['btn-next']);
    const btnProcess = () => {
        if (form.value.shirtsSelected.length) {
            selectProducts();
            setTimeout(() => {
                window.location.href = "/cart";
            }, 300)
        }else{
            formError.value = ['Please select at least one item']
            console.log(formError.value);
            alert('Please select at least one item');
        }
    };

    const goToSemiCustom = () => {
        if (form.value.shirtsSelected.length) {
            selectProducts();
            setTimeout(() => {
                if (useCustomer().getCustomer !== null) {
                    window.location.href = "/semi-custom?page=semi-custom";
                }else {
                    window.location.href = "/semi-custom";
                }
            }, 300)

        }else{
            formError.value = ['Please select at least one item']
            alert('Please select at least one item');
            console.log(formError.value);
        }
    }

    defineExpose({
        form,
    })

</script>

<template>
    <section class="pb-20">
        <div class="page-header">
            <div class="font-bold text-lg text-white lg:text-xl uppercase tracking-widest">Ready to wear</div>
            <div class="w-2/5">
                <label for="default-search" class="mb-2 font-medium text-gray-900 text-sm dark:text-white sr-only">Search</label>
                <div class="relative">
                    <input
                        type="text"
                        id="default-search"
                        v-model="keywords"
                        placeholder="Search for products"
                        @blur="searchProduct"
                        class="block bg-white px-4 py-2 rounded-full w-full text-gray-900 text-sm pe-10" />
                    <button @click="searchProduct" class="absolute inset-y-0 flex items-center end-0 pe-4">
                        <svg class="text-primary-50 size-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="page-header secondary">
            <div class="form-group-inline">
                <div class="form-label">Sort by</div>
                <div class="select-wrapper">
                    <span class="select-icon">
                        ▼
                    </span>
                    <select id="sort" class="block bg-white before:bg-blue-400 p-2.5 focus:border-blue-500 rounded-full focus:ring-blue-500 w-full"
                        v-model="filters.sort"
                        :disabled="isLoading"
                        >
                        <option selected value="0">All</option>
                        <option v-for="(sort, index) in sorts" :key="index" :value="sort">{{ sort }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group-inline">
                <div class="form-label">Color</div>
                <div class="select-wrapper">
                    <span class="select-icon">
                        ▼
                    </span>
                    <select id="color" class="block bg-white before:bg-blue-400 p-2.5 focus:border-blue-500 rounded-full focus:ring-blue-500 w-full"
                        :disabled="isLoading"
                        v-model="filters.color">
                        <option selected value="0">All</option>
                        <option v-for="(color, index) in colours" :key="index" :value="color">{{ color }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group-inline">
                <div class="form-label">Size</div>
                <div class="select-wrapper">
                    <span class="select-icon">
                        ▼
                    </span>
                    <select id="size" class="block bg-white before:bg-blue-400 p-2.5 focus:border-blue-500 rounded-full focus:ring-blue-500 w-full"
                        :disabled="isLoading"
                        v-model="filters.size">
                        <option selected value="0">All</option>
                        <option v-for="(size, index) in sizes" :key="index" :value="size">{{ size }}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="py-10 xl:py-20 container">
            <div class="product-container">
                <div v-if="products.data && !isLoading" v-for="(product) in products.data">
                    <input v-model="form.shirtsSelected" class="hidden" type="checkbox" :value="product.sku" :id="`poduct-${product.id}`">
                    <label class="product-content-item" :for="`poduct-${product.id}`" :class="{ 'border border-secondary-50/40': form.shirtsSelected.includes(product.sku) }">
                        <div class="product-content">
                            <div class="text-[#606060] text-center">{{ product.product_name }}</div>
                            <template v-if="product.product_actual_stocks">
                                <template v-if="product.product_actual_stocks.length">
                                    <div class="my-1 text-center text-primary-300 text-xs" v-for="(stock, index) in product.product_actual_stocks" :key="index">
                                        <span v-if="stock.stock_quantity">{{ stock.store.code }}: {{ stock.stock_quantity }} in stock</span>
                                    </div>
                                </template>
                            </template>
                            <div class="text-[#A3A3A3] text-center text-sm">{{ product.sku }}</div>
                            <div class="text-center text-lg text-secondary-50">{{ priceFormat(product.price) }}</div>
                        </div>
                        <span class="flex justify-center items-center border-4 border-primary-50 rounded-full text-transparent checkbox-inner size-10"></span>
                    </label>
                </div>
                <div v-else-if="isLoading" v-for="i in 4">
                    <div class="flex flex-col justify-center items-center gap-2">
                        <div class="rounded-full w-16 h-3 is-preloader"></div>
                        <div class="rounded-full w-10 h-3 is-preloader"></div>
                        <div class="rounded-full w-20 h-3 is-preloader"></div>
                    </div>
                </div>
                <div v-else-if="!isLoading && !products.data?.length">
                    <div class="flex justify-center items-center text-[#606060] text-center text-lg">No products found</div>
                </div>
                <div v-else>
                    <div class="flex justify-center items-center text-[#606060] text-center text-lg">No products found</div>
                </div>
            </div>
        </div>
        <div class="flex justify-center pb-20">
            <TailwindPagination
                v-if="products"
                :data="products"
                :limit="2"
                :active-classes="['bg-primary-50', 'text-white']"
                @pagination-change-page="getResults"
            />
        </div>


        <div class="right-0 bottom-0 absolute flex">
            <button @click="goToSemiCustom()" class="flex items-center gap-2 bg-primary-50 p-4 lg:p-6 text-white tracking-widest">
                <span>ADD SEMI CUSTOM</span>
                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
            </button>
            <button @click="btnProcess()" class="flex items-center gap-2 bg-secondary-50 p-4 lg:p-6 text-white tracking-widest">
                <span>PROCESS</span>
                <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
            </button>
        </div>

    </section>
</template>

<style scoped>
    input[type="checkbox"] + label span.checkbox-inner {
        @apply border-primary-50;
    }
    input[type="checkbox"]:checked + label span.checkbox-inner {
        @apply border-primary-50 bg-primary-50;
        color: #fff;
        background-image: url("data:image/svg+xml,%3C%3Fxml version='1.0' encoding='UTF-8'%3F%3E%3Csvg width='14px' height='10px' viewBox='0 0 14 10' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'%3E%3C!-- Generator: Sketch 59.1 (86144) - https://sketch.com --%3E%3Ctitle%3Echeck%3C/title%3E%3Cdesc%3ECreated with Sketch.%3C/desc%3E%3Cg id='Page-1' stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'%3E%3Cg id='ios_modification' transform='translate(-27.000000, -191.000000)' fill='%23FFFFFF' fill-rule='nonzero'%3E%3Cg id='Group-Copy' transform='translate(0.000000, 164.000000)'%3E%3Cg id='ic-check-18px' transform='translate(25.000000, 23.000000)'%3E%3Cpolygon id='check' points='6.61 11.89 3.5 8.78 2.44 9.84 6.61 14 15.56 5.05 14.5 4'%3E%3C/polygon%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        background-size: 14px 10px;
    }
    .checkbox-inner {
        @apply inline-block border-2 border-primary-50 rounded-full size-6;
        background: transparent no-repeat center;
    }

    .is-preloader {
        -webkit-animation: shine .5s linear infinite;
        animation: shine .5s linear infinite;
        background: #bebebe;
        background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
        background-size: 200% 100%;
    }

    @keyframes shine {
        to {
            background-position-x: -200%;
        }
    }
</style>
