/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap');
require('../plugins');

import Splide from '@splidejs/splide';
import '@splidejs/splide/css';
window.Splide = Splide;

// import Vue from 'vue';
import { createApp } from "vue";
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import VueNumberFormat from '@coders-tm/vue-number-format';

const pinia = createPinia()
const Vue = createApp()

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('edit-profile', require('./components/user/EditProfile.vue').default);

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

Vue.component('rtw-component', require('./components/user/rtw/index.vue').default);

Vue.component('semi-custom', require('./components/user/semi-cutom/index.vue').default);

Vue.component('cart-component', require('./components/user/cart/index.vue').default);

Vue.component('payment-component', require('./components/user/payment/index.vue').default);

Vue.component('booking-component', require('./components/user/booking/index.vue').default);

Vue.component('print-semi-custom', require('./components/print/semi-custom/SemiCustom.vue').default);

Vue.component('print-bill', require('./components/print/Bill.vue').default);



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app',
// });
pinia.use(piniaPluginPersistedstate)
Vue.use(pinia)
Vue.mount("#app");
