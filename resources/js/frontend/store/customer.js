import { defineStore } from 'pinia'

export const useCustomer = defineStore('customer', {
    state: () => {
        return {
          customer: [],
        }
    },

    getters: {
        getCustomer: (state) => {
            return state.customer;
        },
    },

    actions: {
        setCustomer(customer) {
            this.customer = customer;
        },

        resetCustomer() {
            this.customer = [];
        },
    },

    persist: true,
});
