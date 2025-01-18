import { defineStore } from 'pinia'

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
    },
});
