import { defineStore } from 'pinia';
import { parse, stringify } from 'zipson';

export const usePage = defineStore('currentPage', {
    state: () => {
        return {
          currentPage: null,
        }
    },

    getters: {
        get: (state) => {
            return state.currentPage;
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
