import "es6-promise/auto";
import {createStore} from "vuex";
import author from "./author";
import product from "./product";

const store = createStore({
    modules: {
        author,
        product
    }
})

export default store;