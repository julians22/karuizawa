import { defineStore } from 'pinia'

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

    persist: true,
});
