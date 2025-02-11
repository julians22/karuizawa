import { defineStore } from 'pinia';
import { parse, stringify } from 'zipson';

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

    persist: {
        serializer: {
            deserialize: parse,
            serialize: stringify,
        },
        storage: sessionStorage,
    },
});
