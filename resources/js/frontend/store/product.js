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
          semi_custom_index: null,

          semi_custom_outer: [],
          duplicate_semi_custom_outer: [],
          semi_custom_outer_index: null,

          semi_custom_light_jacket: [],
          duplicate_semi_custom_light_jacket: [],
          semi_custom_light_jacket_index: null,
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
        },

        getSemiCustomOuter: (state) => {
            return state.semi_custom_outer;
        },

        getSemiCustomOuterIndex: (state, index) => {
            return state.semi_custom_outer[index];
        },

        hasDuplicateOuter: (state) => {
            return state.semi_custom_outer_index !== null ? true : false;
        },
        getDuplicateSmOuter: (state) => {
            return state.duplicate_semi_custom_outer;
        },
        getSemiCustomLightJacket: (state) => {
            return state.semi_custom_light_jacket;
        },

        getSemiCustomLightJacketIndex: (state, index) => {
            return state.semi_custom_light_jacket[index];
        },

        hasDuplicateLightJacket: (state) => {
            return state.semi_custom_light_jacket_index !== null ? true : false;
        },
        getDuplicateSmLightJacket: (state) => {
            return state.duplicate_semi_custom_light_jacket;
        },
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

        removeSemiCustom(index) {
            this.semi_custom.splice(index, 1);
        },

        setCustomOuter(custom) {
            this.semi_custom_outer.push(custom)
        },

        setCustomOuterWithKey(custom, key) {
            this.semi_custom_outer[key] = custom;
        },

        setDuplicateSmOuter(custom) {
            this.duplicate_semi_custom_outer = custom;
        },

        setIndexSemiCustomOuter(index) {
            this.semi_custom_outer_index = index
        },

        resetSemiCustomOuter() {
            this.semi_custom_outer = []
            this.semi_custom_outer_index = null
            this.duplicate_semi_custom_outer = [];
        },

        resetDuplicateSmOuter() {
            this.duplicate_semi_custom_outer = [];
        },

        removeSemiCustomOuter(index) {
            this.semi_custom_outer.splice(index, 1);
        },
        setCustomLightJacket(custom) {
            this.semi_custom_light_jacket.push(custom)
        },

        setCustomLightJacketWithKey(custom, key) {
            this.semi_custom_light_jacket[key] = custom;
        },

        setDuplicateSmLightJacket(custom) {
            this.duplicate_semi_custom_light_jacket = custom;
        },

        setIndexSemiCustomLightJacket(index) {
            this.semi_custom_light_jacket_index = index
        },

        resetSemiCustomLightJacket() {
            this.semi_custom_light_jacket = []
            this.semi_custom_light_jacket_index = null
            this.duplicate_semi_custom_light_jacket = [];
        },

        resetDuplicateSmLightJacket() {
            this.duplicate_semi_custom_light_jacket = [];
        },

        removeSemiCustomLightJacket(index) {
            this.semi_custom_light_jacket.splice(index, 1);
        },

        removeProduct(index) {
            this.setProducts.splice(index, 1);
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
