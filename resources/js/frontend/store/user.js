import { defineStore } from 'pinia';
import { parse, stringify } from 'zipson';

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

        getStoreId: (state) => {
            return state.login_user.store_id;
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
