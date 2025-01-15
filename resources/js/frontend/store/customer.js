import { defineStore } from 'pinia'

export const useCustomer = defineStore('customer', {
    state: () => {
        return {
          customer: null,
        }
    },

    getters: {
        getCustomer: (state) => {
            return state.customer;
        },
    },

    persist: true,
});
