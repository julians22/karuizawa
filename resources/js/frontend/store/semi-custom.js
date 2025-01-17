import { defineStore } from 'pinia'

export const useSemiCustom = defineStore('customer', {
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
