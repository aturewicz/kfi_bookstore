import ProductAPI from "../services/product";

const UPDATING_PRODUCT = "UPDATING_PRODUCT",
    UPDATING_PRODUCT_SUCCESS = "UPDATING_PRODUCT_SUCCESS",
    UPDATING_PRODUCT_ERROR = "UPDATING_PRODUCT_ERROR",
    FETCHING_PRODUCTS = "FETCHING_PRODUCTS",
    FETCHING_PRODUCTS_SUCCESS = "FETCHING_PRODUCTS_SUCCESS",
    FETCHING_PRODUCTS_ERROR = "FETCHING_PRODUCTS_ERROR",
    FETCHING_PRODUCT = "FETCHING_PRODUCT",
    FETCHING_PRODUCT_SUCCESS = "FETCHING_PRODUCT_SUCCESS",
    FETCHING_PRODUCT_ERROR = "FETCHING_PRODUCT_ERROR",
    CLEAR_IS_SUCCESS = "CLEAR_IS_SUCCESS",
    CLEAR_ERRORS = "CLEAR_ERRORS",
    CLEAR_ALERTS = "CLEAR_ALERTS";

export default {
    namespaced: true,
    state: {
        isLoading: false,
        isSuccess: false,
        error: null,
        products: [],
        product: null,
        isProduct: false
    },
    getters: {
        isLoading(state) {
            return state.isLoading;
        },
        isSuccess(state) {
            return state.isSuccess;
        },
        hasError(state) {
            return state.error !== null;
        },
        error(state) {
            return state.error;
        },
        hasProducts(state) {

            return state.products.length > 0;
        },
        products(state) {
            return state.products;
        },
        product(state) {
            return state.product;
        },
        isProduct(state) {
            return state.isProduct;
        },
    },
    mutations: {
        [CLEAR_IS_SUCCESS](state) {
            state.isSuccess = false;
        },
        [CLEAR_ERRORS](state) {
            state.error = null;
        },
        [CLEAR_ALERTS](state) {
            state.error = null;
            state.isSuccess = false;
        },
        [UPDATING_PRODUCT](state) {
            state.isLoading = true;
            state.isSuccess = false;
            state.error = null;
        },
        [UPDATING_PRODUCT_SUCCESS](state, product) {
            state.isLoading = false;
            state.isSuccess = true;
            state.error = null;

            const productEan = product.ean;
            const productInProductsIndex = state.products.findIndex(
                (productItem) => productItem.ean === productEan
            );
            state.products[productInProductsIndex] = product;
        },
        [UPDATING_PRODUCT_ERROR](state, error) {
            state.isLoading = false;
            state.isSuccess = false;
            state.error = error;
        },
        [FETCHING_PRODUCTS](state) {
            state.isLoading = true;
            state.error = null;
            state.products = [];
        },
        [FETCHING_PRODUCTS_SUCCESS](state, products) {
            state.isLoading = false;
            state.error = null;
            state.products = products.items;
        },
        [FETCHING_PRODUCTS_ERROR](state, error) {
            state.isLoading = false;
            state.error = error;
            state.products = [];
        },

        [FETCHING_PRODUCT](state) {
            state.isProduct = false;
            state.error = null;
            state.product = null;
        },
        [FETCHING_PRODUCT_SUCCESS](state, product) {
            state.isProduct = true;
            state.error = null;
            state.product = product;
        },
        [FETCHING_PRODUCT_ERROR](state, error) {
            state.isProduct = false;
            state.error = error;
            state.product = null;
        }
    },
    actions: {
        async update({commit}, product) {
            commit(UPDATING_PRODUCT);
            try {
                let response = await ProductAPI.update(product);
                commit(UPDATING_PRODUCT_SUCCESS, product);
                return response.data;
            } catch (error) {
                commit(UPDATING_PRODUCT_ERROR, error);
                return null;
            }
        },
        async fetchAll({commit}) {
            commit(FETCHING_PRODUCTS);
            try {
                let response = await ProductAPI.fetchAll();
                commit(FETCHING_PRODUCTS_SUCCESS, response.data);
                return response.data;
            } catch (error) {
                commit(FETCHING_PRODUCTS_ERROR, error);
                return null;
            }
        },
        async fetchProduct({commit}, ean) {
            commit(FETCHING_PRODUCT);
            try {
                let response = await ProductAPI.fetchProduct(ean);
                commit(FETCHING_PRODUCT_SUCCESS, response.data);
                return response.data;
            } catch (error) {
                commit(FETCHING_PRODUCT_ERROR, error);
                return null;
            }
        },
        async search({commit}, search) {
            commit(FETCHING_PRODUCTS);
            try {
                let response = await ProductAPI.search(search);
                commit(FETCHING_PRODUCTS_SUCCESS, response.data);
                return response.data;
            } catch (error) {
                commit(FETCHING_PRODUCTS_ERROR, error);
                return null;
            }
        },
        clearSuccess({commit}) {
            commit(CLEAR_IS_SUCCESS);
        },
        clearErrors({commit}) {
            commit(CLEAR_ERRORS);
        },
        clearAlerts({commit}) {
            commit(CLEAR_ALERTS);
        }
    }
};