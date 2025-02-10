import { defineStore } from 'pinia';
import { parse, stringify } from 'zipson';

export const useOrder = defineStore('order', {

    state: () => {
        return {
            orderDate: '',
            orderTime: '',
        }
    },

    getters: {
        getOrderDate: (state) => {
            return state.orderDate;
        },

        getOrderTime: (state) => {
            return state.orderTime;
        },

        getDateAndTime: (state) => {
            return state.orderDate + ' ' + state.orderTime;
        },
    },

    persist: {
        serializer: {
            deserialize: parse,
            serialize: stringify,
        },
        storage: sessionStorage,
    },
});
