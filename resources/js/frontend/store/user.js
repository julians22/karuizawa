import { defineStore } from 'pinia'

export const useUser = defineStore('storeUser', {
    state: () => {
        return {
          login_user: null,
        }
    },

    getters: {
        get: (state) => {
            return state.login_user;
        },
    },

    persist: true,
});
