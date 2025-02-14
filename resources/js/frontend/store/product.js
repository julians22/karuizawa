import { reset } from 'laravel-mix/src/Log';
import { defineStore } from 'pinia';
import { parse, stringify } from 'zipson';

export const useProducts = defineStore('products', {
    state: () => {
        return {
          setProducts: [],
          setSlug: [],
          coupon_rtw: 0,
          semi_custom: [],
          duplicate_semi_custom: [],
          semi_custom_index: null
        }
    },

    getters: {
        getSlug: (state) => {
            return state.setSlug;
        },

        getProducts: (state) => {
            return state.setProducts;
        },

        getCouponRtw: (state) => {
            return state.coupon_rtw;
        },

        getSemiCustom: (state) => {
            return state.semi_custom;
        },

        getSemiCustomIndex: (state, index) => {
            return state.semi_custom[index];
        },

        hasDuplicate: (state) => {
            return state.semi_custom_index !== null ? true : false;
        },

        getDuplicateSm: (state) => {
            return state.duplicate_semi_custom;
        }
    },

    actions: {
        setCustom(custom) {
            this.semi_custom.push(custom)
        },

        setCustomWithKey(custom, key) {
            this.semi_custom[key] = custom;
        },

        setDuplicateSm(custom) {
            this.duplicate_semi_custom = custom;
        },

        setIndexSemiCustom(index) {
            this.semi_custom_index = index
        },

        resetSemiCustom() {
            this.semi_custom = []
            this.semi_custom_index = null
            this.duplicate_semi_custom = [];
        },

        resetDuplicateSm() {
            this.duplicate_semi_custom = [];
        },
    },

    persist: {
        // omit: ['duplicate_semi_custom'],
        serializer: {
            deserialize: parse,
            serialize: stringify,
        },
        storage: sessionStorage,
    },
});
