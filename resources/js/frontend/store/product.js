import { defineStore } from 'pinia'

export const useProducts = defineStore('products', {
    state: () => {
        return {
          setProducts: [],
          setSlug: [],
          coupon_rtw: 0,
          semi_custom: []
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
        }
    },

    actions: {
        setCustom(custom) {
            this.semi_custom = custom
        },
    },

    persist: true,
});
