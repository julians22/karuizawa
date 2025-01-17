import { defineStore } from 'pinia'

export const useProducts = defineStore('products', {
    state: () => {
        return {
          setProducts: [],
          setSlug: [],
          coupon_rtw: 0
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
        }
    },

    persist: true,
});
